<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePostAPIRequest;
use App\Http\Requests\API\UpdatePostAPIRequest;
use App\Models\Post;
use App\Models\FarmedTypeGinfo;
use App\Repositories\PostRepository;
use App\Repositories\AssetRepository;
use App\Repositories\PostTypeRepository;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\FarmedTypeGinfoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostTypeResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\FarmedTypeGinfoResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

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
    private $farmedTypeGinfoRepository;
    private $postTypeRepository;

    private $skip       = 5;
    private $perPage    = 10;

    public function __construct(PostRepository $postRepo, AssetRepository $assetRepo, PostTypeRepository $postTypeRepo, FarmedTypeRepository $farmedTypeRepo, FarmedTypeGinfoRepository $farmedTypeGinfoRepo)
    {
        $this->postRepository = $postRepo;
        $this->assetRepository = $assetRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->farmedTypeGinfoRepository = $farmedTypeGinfoRepo;
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
        $posts = Post::get();

        return $this->sendResponse(['all' => PostResource::collection($posts)], 'Posts retrieved successfully');
    }

    private function paginate($data)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $start = ($currentPage - 1) * $this->perPage;

        $currentPageCollection = $data->slice($start, $this->perPage)->all();

        $paginatedData = new LengthAwarePaginator($currentPageCollection, count($data), $this->perPage);

        $paginatedData->setPath(LengthAwarePaginator::resolveCurrentPath());
        return $paginatedData;

    }
    // post_timeline
    public function paginated_posts(Request $request){
        $posts = $this->paginate(Post::accepted()->latest()->get()
        ->skip($this->skip));

        return $this->sendResponse([
            'data' => PostResource::collection($posts->items()),
            'meta' => $posts->toArrayWithoutData(),
        ], '');

    }
    public function timeline(Request $request)
    {
        $posts = Post::accepted()->latest()->get();
        $posts1 = $posts->take($this->skip);
        $posts2 = $this->paginate($posts->skip($this->skip));
        if($posts2->currentPage() > 1)
        {
            $data =  [
                'posts1_count' => null,
                'news_count' => null,
                'posts1' => null,
                'posts2' => [
                    'data' => PostResource::collection($posts2->items()),
                    'meta' => $posts2->toArrayWithoutData(),
                ],
                'news' => null,
                'unread_notifications_count' => null,
                'favorites' => null,
            ];
            $data['posts2'] = [
                'data' => PostResource::collection($posts2->items()),
                'meta' => $posts2->toArrayWithoutData(),
            ];
        }
        else
        {
            $favorites = auth()->user()->favorites;
            $fav_farmed_types_ids = $favorites->pluck('id');
            $fav_farmed_type_ginfos = FarmedTypeGinfo::whereIn('farmed_type_id', $fav_farmed_types_ids)->OrderByDesc('created_at')->limit(10)->get();

            $data =  [
                'posts1_count' => $posts1->count(),
                'news_count' => $fav_farmed_type_ginfos->count(),
                'posts1' => PostResource::collection($posts1),
                'posts2' => [
                    'data' => PostResource::collection($posts2->items()),
                    'meta' => $posts2->toArrayWithoutData(),
                ],
                'news' => FarmedTypeGinfoResource::collection($fav_farmed_type_ginfos),
                'unread_notifications_count' => auth()->user()->unreadNotifications->count(),
                'favorites' => FarmedTypeResource::collection(auth()->user()->favorites),
            ];
        }

        return $this->sendResponse(
           $data,
            'Timeline retrieved successfully');
    }

    public function search($query)
    {
        $posts = Post::accepted()->where('content','like', '%'.$query.'%' )->get();
        return $this->sendResponse(['all' => PostResource::collection($posts)], 'Posts retrieved successfully');
    }

    public function get_posts_by_farmed_type_id($farmed_type_id)
    {
        $posts = Post::accepted()->where('farmed_type_id', $farmed_type_id)->get();
        return $this->sendResponse(['all' => PostResource::collection($posts)], 'Posts retrieved successfully');
    }

    public function get_posts_by_post_type_id($post_type_id)
    {
        $posts = Post::accepted()->where('post_type_id', $post_type_id)->get();
        return $this->sendResponse(['all' => PostResource::collection($posts)], 'Posts retrieved successfully');
    }

    // video_timeline
    public function video_timeline(Request $request)
    {
        $posts = Post::accepted()->whereHas('assets', function ($q)
        {
            $q->whereIn('asset_mime', config('myconfig.video_mimes'));
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
            $post = Post::accepted()->find($id);

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
            $post = Post::accepted()->find($id);

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
            $post = Post::accepted()->find($id);

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

    public function toggle_activate($id)
    {
        try
        {
            $post = $this->postRepository->find($id);

            if (empty($post)) {
                return $this->sendError('Post not found');
            }

            if($post->status == 'accepted')
            {
                $post->status = 'blocked';
                $post->save();
                $msg = 'Post blocked successfully';
                return $this->sendSuccess($msg);
            }
            elseif($post->status == 'blocked')
            {
                $post->status = 'accepted';
                $post->save();
                $msg = 'Post activated successfully';
                return $this->sendSuccess($msg);
            }

        }
        catch(\Throwable $th)
        {throw $th;
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
        $post = Post::accepted()->find($id);

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
            $post = Post::accepted()->find($id);

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
        try
        {
        /** @var Post $post */
        $post = Post::accepted()->find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        $post->delete();

          return $this->sendSuccess('Model deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }
}
