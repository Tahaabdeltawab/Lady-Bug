<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePostAPIRequest;
use App\Http\Requests\API\UpdatePostAPIRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Repositories\AssetRepository;
use App\Repositories\PostTypeRepository;
use App\Repositories\FarmedTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostTypeResource;
use App\Http\Resources\FarmedTypeResource;
use Response;
use App\Http\Resources\AssetResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Class PostController
 * @package App\Http\Controllers\API
 */

class PostAPIController extends AppBaseController
{
    /** @var  PostRepository */
    private $postRepository;
    private $assetRepository;
    private $farmedTypeRepository;
    private $postTypeRepository;

    public function __construct(PostRepository $postRepo, AssetRepository $assetRepo, PostTypeRepository $postTypeRepo, FarmedTypeRepository $farmedTypeRepo)
    {
        $this->postRepository = $postRepo;
        $this->assetRepository = $assetRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->postTypeRepository = $postTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/posts",
     *      summary="Get a listing of the Posts.",
     *      tags={"Post"},
     *      description="Get all Posts",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Post")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */

    public function index(Request $request)
    {
        $posts = $this->postRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => PostResource::collection($posts)], 'Posts retrieved successfully');
    }

    // timeline
    public function timeline(Request $request)
    {
        $posts = $this->postRepository->latest()->get();

        return $this->sendResponse(
            [
                'posts' => PostResource::collection($posts),
                'unread_notifications_count' => auth()->user()->unreadNotifications->count(),
                'favorites' => FarmedTypeResource::collection(auth()->user()->favorites),
            ],
            'Timeline retrieved successfully');
    }

    public function search($query)
    {
        $posts = Post::where('content','like', '%'.$query.'%' )->get();
        return $this->sendResponse(['all' => PostResource::collection($posts)], 'Posts retrieved successfully');
    }

    public function get_posts_by_post_type_id($post_type_id)
    {
        $posts = Post::where('post_type_id', $post_type_id)->get();
        return $this->sendResponse(['all' => PostResource::collection($posts)], 'Posts retrieved successfully');
    }

    // video_timeline
    public function video_timeline(Request $request)
    {
        $posts = Post::whereHas('assets', function ($q)
        {
            $q->whereIn('asset_mime', config('laratrust.taha.video_mimes'));
        })->latest()->get();

        return $this->sendResponse(
            [
                'posts' => PostResource::collection($posts),
                'unread_notifications_count' => auth()->user()->unreadNotifications->count(),
                // 'favorites' => FarmedTypeResource::collection(auth()->user()->favorites),
            ],
            'Timeline retrieved successfully');
    }

    //  solve post
    public function toggle_solve_post($id)
    {
        try
        {
            $post = $this->postRepository->find($id);

            if (empty($post)) {
                return $this->sendError('Post not found');
            }

            if($post->author_id != auth()->id())
            {
                return $this->sendError(__('Sorry, You are not the post author'));
            }

            if($post->solved)
            {
                $do = false;
                $msg = 'Post unsolved successfully';
            }
            else
            {
                $do = true;
                $msg = 'Post solved successfully';
            }

            $this->postRepository->update(['solved' => $do], $id);

            return $this->sendSuccess($msg);
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function posts_relations()
    {
        $posts_types = $this->postTypeRepository->all();
        $farmed_types = $this->farmedTypeRepository->all();

        return $this->sendResponse(
            [
                'posts_types' => PostTypeResource::collection($posts_types),
                'farmed_types' => FarmedTypeResource::collection($farmed_types)
            ], 'Posts retrieved successfully');
    }

    /**
     * @param CreatePostAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/posts",
     *      summary="Store a newly created Post in storage",
     *      tags={"Post"},
     *      description="Store Post",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Post that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Post")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Post"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(/* CreatePostAPI */Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'title' => ['nullable', 'max:200'],
                'content' => ['required'],
                'farm_id' => ['nullable', 'exists:farms,id'],
                'farmed_type_id' => ['nullable'],
                'post_type_id' => ['required', 'exists:post_types,id'],
                'solved' => ['nullable'],
                'assets' => ['nullable','array'],
                'assets.*' => ['nullable', 'max:20000', 'mimes:jpeg,jpg,png,svg,mp4,mov,wmv,qt,asf'] //qt for mov , asf for wmv
            ]);

            // return $this->sendError(json_encode($request->file('assets')[0]->getMimeType()), 777);
            if($validator->fails()){
                $errors = $validator->errors();

                return $this->sendError(json_encode($errors), 777);
            }

            $data['title'] = $request->title;
            $data['content'] = $request->content;
            $data['farm_id'] = $request->farm_id;
            $data['farmed_type_id'] = $request->farmed_type_id;
            $data['post_type_id'] = $request->post_type_id;
            $data['solved'] = $request->solved;
            $data['author_id'] = auth()->id();

            $post = $this->postRepository->save_localized($data);

            if($assets = $request->file('assets'))
            {
                /* if(!is_array($assets))
                {
                    //ERROR YOU CANNOT PASS UPLOADED FILE TO THE QUEUE
                    // dispatch(new \App\Jobs\Upload($request, $post));
                    $currentDate = Carbon::now()->toDateString();
                    $assetsname = 'post-'.$currentDate.'-'.uniqid().'.'.$assets->getClientOriginalExtension();
                    $assetssize = $assets->getSize(); //size in bytes 1k = 1000bytes
                    $assetsmime = $assets->getClientMimeType();

                    $path = $assets->storeAs('assets/posts', $assetsname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                    $url  = Storage::disk('s3')->url($path);

                    $asset = $post->assets()->create([
                        'asset_name'        => $assetsname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetssize,
                        'asset_mime'        => $assetsmime,
                    ]);
                }
                else
                { */
                    foreach($assets as $asset)
                    {
                        //ERROR YOU CANNOT PASS UPLOADED FILE TO THE QUEUE
                        // dispatch(new \App\Jobs\Upload($asset, $post));
                        $currentDate = Carbon::now()->toDateString();
                        $assetname = 'post-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                        $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                        $assetmime = $asset->getClientMimeType();

                        $path = $asset->storeAs('assets/posts', $assetname, 's3');
                        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                        $url  = Storage::disk('s3')->url($path);

                        $asset = $post->assets()->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                        ]);
                    }
                // }
            }

            return $this->sendResponse(new PostResource($post), __('Post saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }



    //  //  //  //  //  L I K E S  &&  D I S L I K E S  //  //  //  //  //

    public function toggle_like($id)
    {
        try
        {
            $post = $this->postRepository->find($id);

            if (empty($post))
            {
                return $this->sendError('post not found');
            }

            $msg = auth()->user()->toggleLike($post);
            return $this->sendSuccess('Post '.$msg);
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }



    public function toggle_dislike($id)
    {
        try
        {
            $post = $this->postRepository->find($id);

            if (empty($post))
            {
                return $this->sendError('post not found');
            }

            $msg = auth()->user()->toggleDislike($post);
            return $this->sendSuccess('Post '.$msg);
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/posts/{id}",
     *      summary="Display the specified Post",
     *      tags={"Post"},
     *      description="Get Post",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Post",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Post"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        return $this->sendResponse(new PostResource($post), 'Post retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePostAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/posts/{id}",
     *      summary="Update the specified Post in storage",
     *      tags={"Post"},
     *      description="Update Post",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Post",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Post that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Post")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Post"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, /* CreatePostAPI */Request $request)
    {
        try
        {
            /** @var Post $post */
            $post = $this->postRepository->find($id);

            if (empty($post)) {
                return $this->sendError('Post not found');
            }

            $validator = Validator::make($request->all(), [
                'title' => ['nullable', 'max:200'],
                'content' => ['required'],
                'farmed_type_id' => ['nullable'],
                'post_type_id' => ['required', 'exists:post_types,id'],
                'solved' => ['nullable'],
                'assets' => ['nullable','array'],
                'assets.*' => ['nullable', 'max:20000', 'mimes:jpeg,jpg,png,svg,mp4,mov,wmv,qt,asf'] //qt for mov , asf for wmv
            ]);

            if($validator->fails()){
                $errors = $validator->errors();

                return $this->sendError(json_encode($errors), 777);
            }



            $data['title'] = $request->title;
            $data['content'] = $request->content;
            $data['farmed_type_id'] = $request->farmed_type_id;
            $data['post_type_id'] = $request->post_type_id;
            $data['solved'] = $request->solved;

            $post = $this->postRepository->save_localized($data, $id);

            if($assets = $request->file('assets'))
            {
              /*   if(!is_array($assets))
                {
                    $currentDate = Carbon::now()->toDateString();
                    $assetsname = 'post-'.$currentDate.'-'.uniqid().'.'.$assets->getClientOriginalExtension();
                    $assetssize = $assets->getSize(); //size in bytes 1k = 1000bytes
                    $assetsmime = $assets->getClientMimeType();

                    $path = $assets->storeAs('assets/posts', $assetsname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                    $url  = Storage::disk('s3')->url($path);

                    $saved_asset = $this->assetRepository->create([
                        'asset_name'        => $assetsname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetssize,
                        'asset_mime'        => $assetsmime,
                    ]);

                    $post->assets()->delete();
                    $asset = $post->assets()->create([
                        'asset_name'        => $assetsname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetssize,
                        'asset_mime'        => $assetsmime,
                    ]);
                }
                else
                { */
                    $post->assets()->delete();

                    foreach($assets as $asset)
                    {
                        //ERROR YOU CANNOT PASS UPLOADED FILE TO THE QUEUE
                        // dispatch(new \App\Jobs\Upload($asset, $post));
                        $currentDate = Carbon::now()->toDateString();
                        $assetname = 'post-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                        $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                        $assetmime = $asset->getClientMimeType();

                        $path = $asset->storeAs('assets/posts', $assetname, 's3');
                        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                        $url  = Storage::disk('s3')->url($path);


                        $assets[] = $post->assets()->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                        ]);
                    }
                // }
            }
            else
            {
                // $post->assets()->delete();
            }

            return $this->sendResponse(new PostResource($post), __('Post saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/posts/{id}",
     *      summary="Remove the specified Post from storage",
     *      tags={"Post"},
     *      description="Delete Post",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Post",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Post $post */
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        $post->delete();

        return $this->sendSuccess('Post deleted successfully');
    }
}
