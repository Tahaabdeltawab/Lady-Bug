<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Helpers\WeatherApi;
use App\Http\Resources\BusinessResource;
use App\Http\Resources\BusinessSmResource;
use App\Http\Resources\BusinessSmWebResource;
use App\Http\Resources\BusinessWebResource;
use App\Http\Resources\BusinessWithTasksResource;
use App\Http\Resources\ConsultancyProfileResource;
use App\Http\Resources\FarmedTypeGinfoResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\FarmedTypeXsResource;
use App\Http\Resources\FarmWithReportsWebResource;
use App\Http\Resources\FarmXsResource;
use App\Http\Resources\PostXsResource;
use App\Http\Resources\ProductXsResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserProfileWebResource;
use App\Http\Resources\UserSmResource;
use App\Models\Business;
use App\Models\Farm;
use App\Models\FarmedTypeGinfo;
use Illuminate\Support\Facades\DB;

class UserWebAPIController extends AppBaseController
{

    public function home()
    {
        $type = request()->type == 'video' ? 'video' : 'post';
        $query = Post::accepted()->$type()->orderByDesc('reactions_count');
        $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
        $posts = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

        $data =  [
            'posts' => [
                'data' => collect(PostXsResource::collection($posts))->where('canBeSeen', true)->values(),
                'meta' => $pag,
            ],
            'favorites' => $pag['currentPage'] == 1 ? FarmedTypeXsResource::collection(auth()->user()->favorites) : null,
            'businesses' => $pag['currentPage'] == 1 ? BusinessSmResource::collection(auth()->user()->allBusinesses) : null,
            'followers' => $pag['currentPage'] == 1 ? UserSmResource::collection(auth()->user()->followers) : null,
        ];
        return $this->sendResponse($data, 'success');
    }

    public function unread_notifications_count()
    {
        $data['unread_notifications_count'] = auth()->user()->unreadNotifications->count();
        return $this->sendResponse($data, 'notifications count retrieved successfully');

    }

    public function user_interests(Request $request)
    {
        $weather_resp = WeatherApi::instance()->weather_api($request);
        $weather_data = $weather_resp['data'];

        $favorites = auth()->user()->favorites;
        $fav_farmed_types_ids = $favorites->pluck('id');

        $query = FarmedTypeGinfo::whereIn('farmed_type_id', $fav_farmed_types_ids)->latest();
        $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
        $fav_farmed_type_ginfos = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

        return $this->sendResponse(
            [
                'weather_data' => $pag['currentPage'] == 1 ? $weather_data : null,
                'favourites' => $pag['currentPage'] == 1 ? FarmedTypeXsResource::collection($favorites) : null,
                'farmed_type_ginfos' => [
                    'data' => FarmedTypeGinfoResource::collection($fav_farmed_type_ginfos),
                    'meta' => $pag,
                    ]
            ], 'Farmed Type General Information retrieved successfully');
    }

    // with today tasks (43)
    public function user_businesses(Request $request)
    {
        try{
            $user = auth()->user();
            $own_businesses = $user->ownBusinesses;
            $shared_businesses = $user->sharedBusinesses($own_businesses->pluck('id'))->get();
            $followed_businesses = $user->followedBusinesses;

            $date = $request->date ?? date('Y-m-d');
            $businesses = auth()->user()->allBusinesses()->with('tasks', function($query) use($date){
                // $query->where('date', $date);
            })->whereHas('tasks', function($q) use($date){
                // $q->where('date', $date);
            })->get();

            return $this->sendResponse([
                'tasks' => BusinessWithTasksResource::collection($businesses),
                'own_businesses' => ['count' => $own_businesses->count(), 'data' => collect(BusinessSmWebResource::collection($own_businesses))->where('canBeSeen', true)->values()],
                'shared_businesses' => ['count' => $shared_businesses->count(), 'data' => collect(BusinessSmWebResource::collection($shared_businesses))->where('canBeSeen', true)->values()],
                'followed_businesses' => ['count' => $followed_businesses->count(), 'data' => collect(BusinessSmWebResource::collection($followed_businesses))->where('canBeSeen', true)->values()],
            ], 'businesses retrieved successfully');
        }catch(\Throwable $th){
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }

    // (45)
    public function business($id)
    {
        $business = Business::find($id);

        if (empty($business))
            return $this->sendError('Business not found');
        $tasks = [];
        foreach($business->farms as $farm){
            $tasks[] = [
                'name' => $farm->farmed_type->name,
                'tasks' => TaskResource::collection($farm->tasks),
            ];
        }

        return $this->sendResponse([
            'business' => BusinessWebResource::make($business),
            'tasks' => ($tasks),
        ], 'Business retrieved successfully');
    }

    public function get_user_profile($id = null){
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            return $this->sendResponse(new UserProfileWebResource($user), 'user retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function get_user_posts($id = null)
    {
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            $query = $user->posts()->accepted()->post();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $posts = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

            return $this->sendResponse(['data' => collect(PostXsResource::collection($posts))->where('canBeSeen', true)->values(), 'meta' => $pag], 'user posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }
    public function get_user_videos($id = null)
    {
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            $query = $user->posts()->accepted()->video();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $videos = $query->skip($pag['skip'])->limit($pag['perPage'])->get();
            return $this->sendResponse(['data' => collect(PostXsResource::collection($videos))->where('canBeSeen', true)->values(), 'meta' => $pag], 'user videos retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function get_user_stories($id = null)
    {
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            $query = $user->posts()->accepted()->story();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $videos = $query->skip($pag['skip'])->limit($pag['perPage'])->get();
            return $this->sendResponse(['data' => collect(PostXsResource::collection($videos))->where('canBeSeen', true)->values(), 'meta' => $pag], 'user stories retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function get_user_articles($id = null)
    {
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            $query = $user->posts()->accepted()->article();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $videos = $query->skip($pag['skip'])->limit($pag['perPage'])->get();
            return $this->sendResponse(['data' => collect(PostXsResource::collection($videos))->where('canBeSeen', true)->values(), 'meta' => $pag], 'user articles retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }
    public function get_user_products($id = null)
    {
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            $query = $user->products();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $products = $query->skip($pag['skip'])->limit($pag['perPage'])->get();
            return $this->sendResponse(['data' => collect(ProductXsResource::collection($products))->where('canBeSeen', true)->values(), 'meta' => $pag], 'user products retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }
    public function get_user_businesses($id = null)
    {
        try{
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            $own_businesses = $user->ownBusinesses;
            $shared_businesses = $user->sharedBusinesses($own_businesses->pluck('id'))->get();
            $followed_businesses = $user->followedBusinesses;
            return $this->sendResponse([
                'own_businesses'        => collect(BusinessSmWebResource::collection($own_businesses))->where('canBeSeen', true)->values(),
                'shared_businesses'     => collect(BusinessSmWebResource::collection($shared_businesses))->where('canBeSeen', true)->values(),
                'followed_businesses'   => collect(BusinessSmWebResource::collection($followed_businesses))->where('canBeSeen', true)->values()
            ], 'businesses retrieved successfully');
        }catch(\Throwable $th){
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }
    // rating
    public function user_rating_details($id = null)
    {
        $user = $id ? User::find($id) : auth()->user();

        if (empty($user))
            return $this->sendError('User not found');

        $data['ladybug'] = [
            'ladybug_rating'    => $user->ladybug_rating() .'%',
            'id_verified'       => $user->id_verified,
            'made_transaction'  => $user->made_transaction,
            'met_ladybug'       => $user->met_ladybug,
            'reactive'          => $user->reactive,
        ];
        $data['users'] = [
            'users_rating' => $user->ratingPercent().'%',
            'ratings_number' => $user->usersRated(),
            'know_personally' => DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 1)->where('answer', 1)->count(),
            'beneficial_knowledge' => DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 2)->where('answer', 1)->count(),
            'dealt_personally' => DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 3)->where('answer', 1)->count(),
            'good_financially' => DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 4)->where('answer', 1)->count(),
            'bad_financially' => DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 5)->where('answer', 1)->count(),
        ];
        $data['followers'] = [
            'count' => $user->followers->count(),
            'data' => UserSmResource::collection($user->followers),
        ];

        return $this->sendResponse($data, 'rating data retrieved successfully');
    }
    // business profile
    public function get_business_posts($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('business not found');
            $query = $business->posts()->accepted()->post();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $posts = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

            return $this->sendResponse(['data' => collect(PostXsResource::collection($posts))->where('canBeSeen', true)->values(), 'meta' => $pag], 'business posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }
    public function get_business_videos($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('business not found');
            $query = $business->posts()->accepted()->video();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $videos = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

            return $this->sendResponse(['data' => collect(PostXsResource::collection($videos))->where('canBeSeen', true)->values(), 'meta' => $pag], 'business videos retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function get_business_stories($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('business not found');
            $query = $business->posts()->accepted()->video();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $videos = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

            return $this->sendResponse(['data' => collect(PostXsResource::collection($videos))->where('canBeSeen', true)->values(), 'meta' => $pag], 'business videos retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }
    public function get_business_products($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('business not found');
            $query = $business->products();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $products = $query->skip($pag['skip'])->limit($pag['perPage'])->get();
            return $this->sendResponse(['data' => collect(ProductXsResource::collection($products))->where('canBeSeen', true)->values(), 'meta' => $pag], 'business products retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function get_business_farms($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('Business not found');
            $query = $business->farms();
            $pag = \Helper::pag($query->count(), request()->perPage, request()->page);
            $farms = $query->skip($pag['skip'])->limit($pag['perPage'])->get();
            return $this->sendResponse(['data' => FarmXsResource::collection($farms), 'meta' => $pag], 'Business farms retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    // farm with reports
    public function farm_with_reports($id)
    {
        try{
            $farm = Farm::find($id);
            if (empty($farm))
                return $this->sendError('Farm not found');
            return $this->sendResponse(new FarmWithReportsWebResource($farm), 'Farm with reports retrieved successfully');
        }catch(\Throwable $th){
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }


}
