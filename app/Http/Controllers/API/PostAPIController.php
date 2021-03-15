<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePostAPIRequest;
use App\Http\Requests\API\UpdatePostAPIRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Repositories\AssetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PostResource;
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

    public function __construct(PostRepository $postRepo, AssetRepository $assetRepo)
    {
        $this->postRepository = $postRepo;
        $this->assetRepository = $assetRepo;
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
                // 'author_id' => ['nullable'],
                'farm_id' => ['nullable', 'exists:farms,id'],
                'farmed_type_id' => ['nullable'],
                'post_type_id' => ['required', 'exists:post_types,id'],
                'solved' => ['nullable'],
                'assets.*' => ['nullable', 'max:20000', 'mimes:jpeg,jpg,png,mp4,mov,ogg,qt']
            ]);

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
                if(!is_array($assets))
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
                        'assetable_type'    => 'post'
                    ]);

                    $post->assets()->attach($saved_asset->id);
                }
                else
                {
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
                // 'author_id' => ['nullable'],
                'farm_id' => ['nullable', 'exists:farms,id'],
                'farmed_type_id' => ['nullable'],
                'post_type_id' => ['required', 'exists:post_types,id'],
                'solved' => ['nullable'],
                'assets.*' => ['nullable', 'max:20000', 'mimes:jpeg,jpg,png,mp4,mov,ogg,qt']
            ]);

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
            
            $post = $this->postRepository->save_localized($data, $id);
            
            if($assets = $request->file('assets'))
            {
                if(!is_array($assets))
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
                        'assetable_type'    => 'post'
                    ]);

                    $post->assets()->sync([$saved_asset->id]);
                }
                else
                {
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
                        
                        $saved_assets[] = $this->assetRepository->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                            'assetable_type'    => 'post'
                        ]);
    
                    }
                    $saved_assets_ids = collect($saved_assets)->pluck('id')->all();
                    $post->assets()->sync($saved_assets_ids);
                }
            }
            else
            {
                $post->assets()->sync([]);
            }

            return $this->sendResponse(new PostResource($post), __('Post saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500); 
        }

       

        $post = $this->postRepository->save_localized($input, $id);

        return $this->sendResponse(new PostResource($post), 'Post updated successfully');
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
