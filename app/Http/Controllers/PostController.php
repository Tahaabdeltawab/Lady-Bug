<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Repositories\AssetRepository;
use App\Http\Resources\AssetResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PostController extends AppBaseController
{
    /** @var  PostRepository */
    private $postRepository;
    private $assetRepository;

    public function __construct(PostRepository $postRepo, AssetRepository $assetRepo)
    {
        $this->postRepository = $postRepo;
        $this->assetRepository = $assetRepo;
    }

    /**
     * Display a listing of the Post.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $posts = $this->postRepository->all();

        return view('posts.index')
            ->with('posts', $posts);
    }

    /**
     * Show the form for creating a new Post.
     *
     * @return Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created Post in storage.
     *
     * @param CreatePostRequest $request
     *
     * @return Response
     */
    public function store(/* CreatePostAPI */Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'title' => ['nullable', 'max:200'],
                'content' => ['required'],
                'author_id' => ['nullable'],
                'farm_id' => ['nullable', 'exists:farms,id'],
                'farmed_type_id' => ['nullable'],
                'post_type_id' => ['required', 'exists:post_types,id'],
                'solved' => ['nullable'],
                'assets.*' => ['nullable', 'max:50000', 'mimes:jpeg,jpg,png,mp4,mov,ogg,qt']
            ]);

            if($validator->fails()){
                $errors = $validator->errors();
                
                return $this->sendError(json_encode($errors), 777);
            }

            $post = $this->postRepository->save_localized($request->all());
            
            if($assets = $request->file('assets'))
            {
                foreach($assets as $asset)
                {
                    // ERROR YOU CANNOT PASS UPLOADED FILE TO THE QUEUE
                    // dispatch(new \App\Jobs\Upload($request->all(), $post));
                    $currentDate = Carbon::now()->toDateString();
                    $assetname = 'post-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                    $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                    $assetmime = $asset->getClientMimeType();
                            
                    $path = $asset->storeAs('assets/posts', $assetname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);
                    
                    $url  = Storage::disk('s3')->url($path);
                    
                    $saved_asset = $this->assetRepository->create([
                        'asset_name'        => $assetname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetsize,
                        'asset_mime'        => $assetmime,
                        'assetable_type'    => 'post'
                    ]);

                    $post->assets()->attach($saved_asset->id);
                }
            }

            Flash::success('Post saved successfully.');

            return redirect(route('posts.index'));
        }
        catch(\Throwable $th)
        {
            Flash::error($th->getMessage());
        }
    }

    /**
     * Display the specified Post.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified Post.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified Post in storage.
     *
     * @param int $id
     * @param UpdatePostRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePostRequest $request)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $post = $this->postRepository->update($request->all(), $id);

        Flash::success('Post updated successfully.');

        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified Post from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $this->postRepository->delete($id);

        Flash::success('Post deleted successfully.');

        return redirect(route('posts.index'));
    }
}
