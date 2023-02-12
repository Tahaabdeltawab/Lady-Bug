<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePostAPIRequest;
use App\Models\Post;
use App\Models\FarmedTypeGinfo;
use App\Repositories\PostRepository;
use App\Repositories\PostTypeRepository;
use App\Repositories\FarmedTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BusinessSmResource;
use App\Http\Resources\PostTypeResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\FarmedTypeGinfoResource;
use App\Http\Resources\FarmedTypeXsResource;
use App\Http\Resources\PostAdminResource;
use App\Http\Resources\PostXsResource;
use App\Http\Resources\UserSmResource;
use App\Models\Business;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PostController
 * @package App\Http\Controllers\API
 */

class PostAPIController extends AppBaseController
{
    /** @var  PostRepository */
    private $postRepository;
    private $farmedTypeRepository;
    private $postTypeRepository;

    private $skip       = 5;
    private $perPage    = 10;

    public function __construct(PostRepository $postRepo, PostTypeRepository $postTypeRepo, FarmedTypeRepository $farmedTypeRepo)
    {
        $this->postRepository = $postRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->postTypeRepository = $postTypeRepo;

        $this->middleware('permission:posts.index')->only(['admin_index']);
        $this->middleware('permission:posts.update')->only(['toggle_activate']);
        $this->middleware('permission:posts.destroy')->only(['admin_destroy']);
    }


    // admin
    public function admin_index(Request $request)
    {
        $posts = $this->postRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => PostAdminResource::collection($posts['all']), 'meta' => $posts['meta']], 'Posts retrieved successfully');
    }
    public function admin_show($id)
    {
        /** @var Post $post */
        $post = Post::find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        return $this->sendResponse(new PostAdminResource($post), 'Post retrieved successfully');
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
        $posts = $this->paginate(
            Post::accepted()->latest()->get()->skip($this->skip)
        );

        return $this->sendResponse([
            'data' => collect(PostXsResource::collection($posts->items()))->where('canBeSeen', true)->values(),
            'meta' => $posts->toArrayWithoutData(),
        ], '');

    }
    public function timeline(Request $request)
    {
        // * try to apply this to avoid N+1 if applicable (if no pagination)
        // ->with(['dislikers', 'likers', 'comments', 'assets', 'author', 'business'])->withCount(['dislikers', 'likers', 'comments'])
        $posts = Post::accepted()->post()->orderByDesc('reactions_count')->get();
        //  ترتيب المنشورات
        // الأشخاص الذين يتابعهم المستخدم
        $followings_ids = auth()->user()->followedUsers->pluck('id');
        // المحاصيل الموجودة في مفضلة المستخدم
        $favorites_ids = auth()->user()->favorites->pluck('id');
        // المنشورات التي تخص أشخاص يتابعهم المستخدم وتخص محاصيل في مفضلة المستخدم
        $favfollowings_posts = $posts->whereIn('author_id', $followings_ids)->whereIn('farmed_type_id', $favorites_ids);
        $remnant_posts = $posts->whereNotIn('id', $favfollowings_posts->pluck('id'));

        // المنشورات التي تخص أشخاص يتابعهم المستخدم
        $followings_posts = $remnant_posts->whereIn('author_id', $followings_ids);
        $remnant_posts = $remnant_posts->whereNotIn('id', $followings_posts->pluck('id'));

        // المنشورات التي تخص محاصيل في مفضلة المستخدم
        $favourites_posts = $remnant_posts->whereIn('farmed_type_id', $favorites_ids);
        $remnant_posts = $remnant_posts->whereNotIn('id', $favourites_posts->pluck('id'));

        $posts = $favfollowings_posts->merge($followings_posts)->merge($favourites_posts)->merge($remnant_posts);

        $posts1 = $posts->take($this->skip);
        $posts1_c = collect(PostXsResource::collection($posts1))->where('canBeSeen', true)->values();
        $posts2 = $this->paginate($posts->skip($this->skip));
        $posts2_c = collect(PostXsResource::collection($posts2->items()))->where('canBeSeen', true)->values();
        if($posts2->currentPage() > 1)
        {
            $data =  [
                'posts1_count' => null,
                'news_count' => null,
                'posts1' => null,
                'posts2' => [
                    'data' => $posts2_c,
                    'meta' => $posts2->toArrayWithoutData(),
                ],
                'news' => null,
                'unread_notifications_count' => null,
                'favorites' => null,
            ];
        }
        else
        {
            $favorites = auth()->user()->favorites;
            $fav_farmed_types_ids = $favorites->pluck('id');
            $fav_farmed_type_ginfos = FarmedTypeGinfo::whereIn('farmed_type_id', $fav_farmed_types_ids)->OrderByDesc('created_at')->limit(10)->get();

            $data =  [
                'posts1_count' => $posts1_c->count(),
                'news_count' => $fav_farmed_type_ginfos->count(),
                'posts1' => $posts1_c,
                'posts2' => [
                    'data' => $posts2_c,
                    'meta' => $posts2->toArrayWithoutData(),
                ],
                'news' => FarmedTypeGinfoResource::collection($fav_farmed_type_ginfos),
                'unread_notifications_count' => auth()->user()->unreadNotifications->count(),
                'favorites' => FarmedTypeXsResource::collection(auth()->user()->favorites),
            ];
        }

        return $this->sendResponse(
           $data,
            'Timeline retrieved successfully');
    }

     // video_timeline
     public function video_timeline(Request $request)
     {
         $posts = Post::accepted()->video()->orderByDesc('reactions_count')->get();

         return $this->sendResponse(
             [
                 'posts' => collect(PostXsResource::collection($posts))->where('canBeSeen', true)->values(),
                 'unread_notifications_count' => auth()->user()->unreadNotifications()->count(),
             ],
             'Timeline retrieved successfully');
     }

    public function search($query, Request $request)
    {
        $query = \Str::lower(trim($query));
        $posts = Post::accepted()->where('content','like', '%'.$query.'%' )
        ->when($request->type == 'video', function($q){
            return $q->video();
        })
        ->get();
        return $this->sendResponse(['all' => collect(PostXsResource::collection($posts))->where('canBeSeen', true)->values()], 'Posts retrieved successfully');
    }

    public function get_posts_by_farmed_type_id($farmed_type_id)
    {
        $posts = Post::accepted()->where('farmed_type_id', $farmed_type_id)->get();
        return $this->sendResponse(['all' => collect(PostXsResource::collection($posts))->where('canBeSeen', true)->values()], 'Posts retrieved successfully');
    }

    public function get_posts_by_post_type_id($post_type_id)
    {
        $posts = Post::accepted()->where('post_type_id', $post_type_id)->get();
        return $this->sendResponse(['all' => collect(PostXsResource::collection($posts))->where('canBeSeen', true)->values()], 'Posts retrieved successfully');
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

    public function store(CreatePostAPIRequest $request)
    {
        try
        {
            \DB::beginTransaction();
            $post_type_id = $request->post_type_id;
            if($request->business_id){
                $business = Business::find($request->business_id);
                if(!auth()->user()->hasPermission("create-post", $business))
                    return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

                $post_type_id = 4;
            }
            $data['shared_id'] = $request->shared_id;
            $data['title'] = $request->title;
            $data['content'] = $request->content;
            $data['business_id'] = $request->business_id;
            $data['farmed_type_id'] = $request->farmed_type_id;
            $data['post_type_id'] = $post_type_id;
            $data['solved'] = $request->solved;
            $data['type'] = $request->type ?: 'post'; // or story or article
            $data['author_id'] = auth()->id();

            $post = $this->postRepository->create($data);

            if($assets = $request->file('assets'))
            {
                    foreach($assets as $asset)
                    {
                        $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'post');
                        $post->assets()->create($oneasset);
                    }
            }

            // notify the original post author that someone shared his post
            if($request->shared_id && $post->author_id != $post->shared->author_id)
                $post->shared->author->notify(new \App\Notifications\TimelineInteraction($post, 'post_share'));

            // notify the owner followers
            if($business = $post->business){
                // send notif to business participants who are following the business
                $followers = $business->isSecret() ? $business->following_participants() : $business->followers;
                foreach($followers as $follower){
                    $follower->notify(new \App\Notifications\TimelineInteraction($post, '', $business->com_name));
                }
            }else{
                $post->loadMissing('author.followers');
                foreach($post->author->followers as $follower){
                    $follower->notify(new \App\Notifications\TimelineInteraction($post));
                }
            }
            \DB::commit();
            return $this->sendResponse(new PostXsResource($post), __('Post saved successfully'));
        }
        catch(\Throwable $th)
        {
            \DB::rollback();
            return $this->sendError($th->getMessage(), 500);
        }
    }



    // LIKES && DISLIKES

    public function toggle_like($id)
    {
        try
        {
            $post = Post::accepted()->find($id);

            if (empty($post))
                return $this->sendError('post not found');
            $like = auth()->user()->toggleLike($post); // $like may be Like obj (in case of creating) or 1 (in case of deleting)
            $post->updateReactions();
            $like_model = config('like.like_model');
            if($like instanceOf  $like_model)
            {
                $msg = 'Post liked successfully';
                if($like->user_id != $post->author_id)
                $post->author->notify(new \App\Notifications\TimelineInteraction($like));
            }
            else
            {
                $msg = 'Post like removed successfully';
            }

            return $this->sendResponse([
                'likers_count' => $post->likers()->count(),
                'dislikers_count' => $post->dislikers()->count(),
                'comments_count' => $post->comments()->count(),
                'liked_by_me' => $post->likers()->where('users.id', auth()->id())->count() ? true : false,
                'disliked_by_me' => $post->dislikers()->where('users.id', auth()->id())->count() ? true : false
            ], $msg);
        }
        catch(\Throwable $th)
        {
            throw $th;
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

            $dislike = auth()->user()->toggleDislike($post); // $dislike may be Like obj (in case of creating) or 1 (in case of deleting)
            $post->updateReactions();
            $like_model = config('like.like_model');
            if($dislike instanceOf  $like_model)
            {
                $msg = 'Post disliked successfully';
                // if($dislike->user_id != $post->author_id)
                // $post->author->notify(new \App\Notifications\TimelineInteraction($dislike));
            }
            else
            {
                $msg = 'Post dislike removed successfully';
            }

            return $this->sendResponse([
                'likers_count' => $post->likers()->count(),
                'dislikers_count' => $post->dislikers()->count(),
                'comments_count' => $post->comments()->count(),
                'liked_by_me' => $post->likers()->where('users.id', auth()->id())->count() ? true : false,
                'disliked_by_me' => $post->dislikers()->where('users.id', auth()->id())->count() ? true : false
            ], $msg);
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

    public function show($id)
    {
        /** @var Post $post */
        $post = Post::accepted()->find($id);

        if (empty($post)) {
            return $this->sendError('Post not found');
        }

        return $this->sendResponse(new PostXsResource($post), 'Post retrieved successfully');
    }

    public function update($id, CreatePostAPIRequest $request)
    {
        try
        {
            /** @var Post $post */
            $post = Post::accepted()->find($id);

            if (empty($post))
                return $this->sendError('Post not found');

            if($post->business_id){
                $business = Business::find($post->business_id);
                if(!auth()->user()->hasPermission("edit-post", $business))
                    return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));
            }

            $data['title'] = $request->title;
            $data['content'] = $request->content;
            $data['farmed_type_id'] = $request->farmed_type_id;
            $data['post_type_id'] = $request->post_type_id;
            $data['solved'] = $request->solved;

            $post = $this->postRepository->update($data, $id);

            if($assets = $request->file('assets'))
            {
                foreach ($post->assets as $ass) {
                    $ass->delete();
                }
                foreach($assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'post');
                    $assets[] = $post->assets()->create($oneasset);
                }
            }

            return $this->sendResponse(new PostXsResource($post), __('Post saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function admin_destroy($id)
    {
        return $this->destroy($id);
    }

    public function destroy($id)
    {
        try
        {
            /** @var Post $post */
            $post = Post::find($id);

            if (empty($post)) {
                return $this->sendError('Post not found');
            }

            foreach($post->assets as $ass){
               $ass->delete();
            }
            $post->delete();

            return $this->sendSuccess('Model deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            throw $th;
            return $this->sendError('Error deleting the model');
        }
    }
}
