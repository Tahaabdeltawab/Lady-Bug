<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\API\CreateUserFavoritesAPIRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Post;
use App\Models\Product;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\FarmedTypeGinfoResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Repositories\ProductRepository;
use App\Repositories\FarmedTypeGinfoRepository;

use App\Http\Helpers\WeatherApi;
use App\Http\Requests\API\CreateNotificationSettingAPIRequest;
use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\RateUserRequest;
use App\Http\Requests\API\UpdateProfileAPIRequest;
use App\Http\Resources\BusinessResource;
use App\Http\Resources\BusinessXsResource;
use App\Http\Resources\ConsultancyProfileResource;
use App\Http\Resources\PostXsResource;
use App\Http\Resources\ProductXsResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserProfileSmResource;
use App\Http\Resources\UserProfileWebResource;
use App\Http\Resources\UserSmResource;
use App\Http\Resources\UserWithPostsResource;
use App\Http\Resources\UserXsResource;
use App\Models\NotificationSetting;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    private $productRepository;
    private $farmedTypeGinfoRepository;

    public function __construct(
        UserRepository $userRepo,
        ProductRepository $productRepo,
        FarmedTypeGinfoRepository $farmedTypeGinfoRepo
        )
    {
        $this->userRepository = $userRepo;
        $this->productRepository = $productRepo;
        $this->farmedTypeGinfoRepository = $farmedTypeGinfoRepo;

        $this->middleware('permission:users.index')->only(['index']);
        $this->middleware('permission:users.show')->only(['show']);
        $this->middleware('permission:users.admin_show')->only(['admin_show']);
        $this->middleware('permission:users.admin_index')->only(['admin_index']);
        $this->middleware('permission:users.store')->only(['store']);
        $this->middleware('permission:users.update')->only(['update_user_roles', 'toggle_activate_user']);
        // this is made in the api.php file because the user and admin both can use update method,
        //if you did it here, the request will be required for the user and admin and this is incorrect because it should be only for the admin
        // $this->middleware('permission:users.update')->only(['update']);
        $this->middleware('permission:users.destroy')->only(['destroy']);
    }


    // app users
    public function index(Request $request)
    {
        $users = User::user()->get();

        return $this->sendResponse(['all' => UserResource::collection($users)], 'Users retrieved successfully');
    }

    // app admins
    public function admin_index(Request $request)
    {
        $users = User::admin()->get();

        return $this->sendResponse(['all' => UserResource::collection($users)], 'Users retrieved successfully');
    }


    public function search($query)
    {
        $query = \Str::lower(trim($query));
        $users = User::where('name','like', '%'.$query.'%' )->orWhere('email','like', '%'.$query.'%')->orWhere('mobile','like', '%'.$query.'%')->get();
        return $this->sendResponse(['all' => UserResource::collection($users)], 'Users retrieved successfully');
    }



    public function user_products(Request $request)
    {
        try{
            $products = $this->productRepository->where(['seller_id' => auth()->id()])->all();

            return $this->sendResponse(['all' => ProductXsResource::collection($products)], 'User products retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    //user interests -> farmed_type_ginfos, products, interests
    public function user_interests(Request $request)
    {
        $weather_resp = WeatherApi::instance()->weather_api($request);
        $weather_data = $weather_resp['data'];

        $favorites = auth()->user()->favorites;
        $fav_farmed_types_ids = $favorites->pluck('id');

        $fav_farmed_type_ginfos = $this->farmedTypeGinfoRepository->whereIn(['farmed_type_id' => $fav_farmed_types_ids])->all();

        $fav_products = Product::whereHas('farmedTypes', function($q)use($fav_farmed_types_ids){
            return $q->whereIn('farmed_types.id', $fav_farmed_types_ids);
        })->limit(10)->get();
        $latest_products = Product::latest()->limit(10)->get();

        return $this->sendResponse(
            [
                'weather_data' => $weather_data,
                'favourites' => FarmedTypeResource::collection($favorites),
                'farmed_type_ginfos' => FarmedTypeGinfoResource::collection($fav_farmed_type_ginfos),
                'favorite_products' => ProductXsResource::collection($fav_products),
                'latest_products' => ProductXsResource::collection($latest_products)
            ], 'Farmed Type General Information relations retrieved successfully');
    }




    // NOTIFICATIONS

    public function notification_settings(CreateNotificationSettingAPIRequest $request)
    {
        $ns = NotificationSetting::updateOrCreate(['user_id' => auth()->id()], $request->validated());
        return $this->sendResponse($ns, 'Notification settings updated successfully');
    }

    public function get_my_notification_settings()
    {
        $ns = NotificationSetting::where('user_id', auth()->id())->first();
        return $this->sendResponse($ns, 'Notification settings retrieved successfully');
    }

    public function toggle_notifiable()
    {
        $msg = auth()->user()->is_notifiable ? 'User became not notifiable Successfully' : 'User became notifiable Successfully';
        auth()->user()->is_notifiable = !auth()->user()->is_notifiable;
        auth()->user()->save();

        return $this->sendSuccess($msg);
    }

    public function get_notifications()
    {
        try{
            if(auth()->user()->is_notifiable)
            {
                $user = auth()->user();
                $notifications = $user->notifications ;
                $user->unreadNotifications->markAsRead();

                return $this->sendResponse(['all' => NotificationResource::collection($notifications)], 'Notifications retrieved successfully');
            }else
                return $this->sendError(__('Your Notifications are off, turn them on to see your notifications.'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function unread_notifications_count()
    {
        $data['unread_notifications_count'] = auth()->user()->unreadNotifications->count();
        return $this->sendResponse($data, 'notifications count retrieved successfully');

    }
    public function read_notification($id)
    {
        try{
            $notification = auth()->user()->unreadNotifications->where('id', $id)->first();
            if(!$notification)
            {
                return $this->sendError('Notification not found');
            }
            $notification->markAsRead();

            return $this->sendSuccess('Notifications read successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }
    public function unread_notification($id)
    {
        try{
            $notification = auth()->user()->readNotifications->where('id', $id)->first();
            if(!$notification)
            {
                return $this->sendError('Notification not found');
            }
            $notification->markAsUnread();

            return $this->sendSuccess('Notifications unread successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    // web

    // end web

    public function get_user_with_posts($id = null)
    {
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            return $this->sendResponse(new UserWithPostsResource($user), 'user with posts retrieved successfully');
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
            $posts = $user->posts()->accepted()->post()->get();
            return $this->sendResponse(PostXsResource::collection($posts), 'user posts retrieved successfully');
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
            $videos = $user->posts()->accepted()->video()->get();
            return $this->sendResponse(PostXsResource::collection($videos), 'user videos retrieved successfully');
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
            $videos = $user->posts()->accepted()->story()->get();
            return $this->sendResponse(PostXsResource::collection($videos), 'user stories retrieved successfully');
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
            $videos = $user->posts()->accepted()->article()->get();
            return $this->sendResponse(PostXsResource::collection($videos), 'user articles retrieved successfully');
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
            $products = $user->products;
            return $this->sendResponse(ProductXsResource::collection($products), 'user products retrieved successfully');
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
                'cons'                  => $user->consultancyProfile ? new ConsultancyProfileResource($user->consultancyProfile) : null,
                'own_businesses'        => BusinessResource::collection($own_businesses),
                'shared_businesses'     => BusinessResource::collection($shared_businesses),
                'followed_businesses'   => BusinessResource::collection($followed_businesses)
            ], 'businesses retrieved successfully');
        }catch(\Throwable $th){
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function user_liked_posts()
    {
        try
        {
            $likeables = [];
            if($likes = auth()->user()->likes()->withType(Post::class)->with('likeable')->get())
            {
                foreach ($likes as $like)
                {
                    $likeables[] = $like->likeable;
                }
            }
            return $this->sendResponse(['all' => PostResource::collection(collect($likeables)->where('status', 'accepted'))], 'User liked posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function user_disliked_posts()
    {
        try
        {
            $dislikeables = [];
            if($dislikes = auth()->user()->dislikes()->withType(Post::class)->with('likeable')->get())
            {
                foreach ($dislikes as $dislike){
                    $dislikeables[] = $dislike->likeable;
                }
            }
            return $this->sendResponse(['all' => PostResource::collection(collect($dislikeables)->where('status', 'accepted'))], 'User disliked posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }





    public function my_favorites()
    {
        $my_favorites = auth()->user()->favorites;

        return $this->sendResponse(['all' => FarmedTypeResource::collection($my_favorites)], 'User selected favorites retrieved successfully');
    }


    public function store_favorites(CreateUserFavoritesAPIRequest $request)
    {
        $input = $request->validated();
        auth()->user()->favorites()->sync($input['favorites']);
        return $this->sendSuccess(__('User favorites saved successfully'));
    }




    // FOLLOW

    public function toggle_follow($id)
    {
        try
        {
            $user = $this->userRepository->find($id);

            if (empty($user))
                return $this->sendError('User not found');

            if(auth()->user()->isFollowing($user))
            {
                auth()->user()->unfollow($user);
                return $this->sendSuccess(__('unfollowed', ['name' => $user->name]));
            }
            else
            {
                auth()->user()->follow($user);
                $user->notify(new \App\Notifications\Follow(auth()->user()));
                return $this->sendSuccess(__('followed', ['name' => $user->name]));
            }
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function user_followers($id = null)
    {
        $user = $id ? User::findOrFail($id) : auth()->user();
        $user_followers = $user->followers;

        return $this->sendResponse(['count' => $user_followers->count(), 'all' => UserSmResource::collection($user_followers)], 'User followers retrieved successfully');
    }

    public function user_followings($id = null)
    {
        $user = $id ? User::findOrFail($id) : auth()->user();
        if(request()->type == 'user'){
            $my_followings = $user->followedUsers;
            return $this->sendResponse(['count' => $my_followings->count(), 'all' => UserSmResource::collection($my_followings)], 'Followed users retrieved successfully');
        }else{
            $my_followings = $user->followedBusinesses;
            return $this->sendResponse(['count' => $my_followings->count(), 'all' => BusinessXsResource::collection($my_followings)], 'Followed businesses retrieved successfully');
        }
    }




    // RATE

    // admin
    public function ladybug_rating(User $user, Request $request)
    {
        $user->id_verified = $request->id_verified;
        $user->made_transaction = $request->made_transaction;
        $user->met_ladybug = $request->met_ladybug;
        $user->reactive = $request->reactive;
        $user->save();

        return $this->sendSuccess('updated successfully');
    }
    // user
    public function user_rating_details($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user))
            return $this->sendError('User not found');

        $data['users_rating'] = $user->ratingPercent().'%';
        $data['ratings_number'] = $user->usersRated();
        $data['know_personally'] = DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 1)->where('answer', 1)->count();
        $data['beneficial_knowledge'] = DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 2)->where('answer', 1)->count();
        $data['dealt_personally'] = DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 3)->where('answer', 1)->count();
        $data['good_financially'] = DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 4)->where('answer', 1)->count();
        $data['bad_financially'] = DB::table('rating_rating_question')->where('rateable_id', $id)->where('rating_question_id', 5)->where('answer', 1)->count();

        return $this->sendResponse($data, 'rating data retrieved successfully');
    }
    public function rate(RateUserRequest $request)
    {
        try
        {
            $user = $this->userRepository->find($request->user);

            if (empty($user))
                return $this->sendError('User not found');

            $user->rateOnce($request->rating, $request->questions);
            return $this->sendSuccess("You have rated $user->name with $request->rating stars successfully");
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    // admins
    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.null.',id',
                'mobile' => 'required|string|max:255|unique:users,mobile,'.null.',id',
                'password' => 'required|string|min:8|confirmed',
                'human_job_id' => 'nullable|exists:human_jobs,id',
                'photo' => 'nullable|max:5000|image',
                'roles'   => 'nullable|array',
                'roles.*' => 'nullable|exists:roles,id',
            ]);

            if($validator->fails()){
                $errors = $validator->errors();
                if ($errors->has('email') && $errors->has('mobile'))
                    $code = 5031;
                elseif($errors->has('email') && !$errors->has('mobile'))
                    $code = 5032;
                elseif(!$errors->has('email') && $errors->has('mobile'))
                    $code = 5033;
                elseif($errors->has('photo'))
                    $code = 5034;
                else
                    $code = 5030;
                return $this->sendError($validator->errors()->first(), $code);
            }

            DB::beginTransaction();
            $user = $this->userRepository->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'type'  => 'app_admin',
                'mobile' => $request->get('mobile'),
                'human_job_id' => $request->get('human_job_id'),
                'password' => Hash::make($request->get('password')),
            ]);

            if (isset($request->roles) && ! empty($request->roles))
            {
                $user->attachRoles($request->roles);
            }

            if($photo = $request->file('photo'))
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo);
                $user->asset()->create($oneasset);
            }

            DB::commit();

            $data = [
                'user' => new UserResource($user),
            ];
            return $this->sendResponse($data, __('Success'));
        }
        catch(\Throwable $th)
        {
            DB::rollback();
            return $this->sendError($th->getMessage(), 500);
        }
    }


    //admins
    public function update_user_roles(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'user' => 'integer|required|exists:users,id',
                'roles' => 'nullable|array',
                'roles.*' => 'exists:roles,id',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $user = $this->userRepository->find($request->user);

            $admin_allowed_roles = Role::appAllowedRoles()->pluck('id')->toArray();

            if (array_diff($request->roles,$admin_allowed_roles))
            {
                return $this->sendError('Not allowed roles');
            }

            $user->syncRoles($request->roles);

            return $this->sendResponse(new UserResource($user), __('User roles saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }



    // users
    public function show($id)
    {
        $user = User::user()->find($id);

        if (empty($user)) {
            return $this->sendError('Admin not found');
        }

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully');
    }

    public function get_user_profile($id = null){
        try
        {
            $user = $id ? User::find($id) : auth()->user();
            if (empty($user))
                return $this->sendError('user not found');
            return $this->sendResponse(new UserProfileSmResource($user), 'user retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }


    // admins
    public function admin_show($id)
    {
        $user = User::admin()->find($id);

        if (empty($user)) {
            return $this->sendError('Admin not found');
        }

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully');
    }

    public function toggle_activate_user($id)
    {
        try
        {
            $user = $this->userRepository->find($id);

            if (empty($user)) {
                return $this->sendError('User not found');
            }

            if($user->status == 'accepted')
            {
                $validator = Validator::make(request()->all(), [
                    'blocked_until' => 'nullable|date_format:Y-m-d',
                    'block_days' => 'nullable|integer'
                ]);
                if($validator->fails())
                    return $this->sendError($validator->errors()->first(), 757);

                if($block_days = request()->block_days)
                    $blocked_until = today()->addDays($block_days);

                if(request()->blocked_until)
                    $blocked_until = request()->blocked_until;

                $user->status = 'blocked';
                if(isset($blocked_until))
                $user->blocked_until = $blocked_until;
                // return $blocked_until;

                $user->save();
                $msg = 'User blocked successfully';
                return $this->sendSuccess($msg);
            }
            elseif($user->status == 'blocked')
            {
                $user->status = 'accepted';
                $user->blocked_until = null;
                $user->save();
                $msg = 'User activated successfully';
                return $this->sendSuccess($msg);
            }

        }
        catch(\Throwable $th)
        {throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }

    // users and admins
    public function update($id, /* CreateUserAPI */Request $request)
    {
        try
        {

            $user = $this->userRepository->find($id);

            if (empty($user)) {
                return $this->sendError('User not found');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$id.',id',
                'mobile' => 'required|string|max:255|unique:users,mobile,'.$id.',id',
                'password' => 'nullable|string|min:8|confirmed',
                'human_job_id' => 'nullable|exists:human_jobs,id',
                'income' => 'nullable|integer|min:0',
                'dob' => 'nullable|date_format:Y-m-d',
                'city_id' => 'nullable|exists:cities,id',
                'photo' => 'nullable|max:5000|image',
                'roles'   => 'nullable|array',
                'roles.*' => 'nullable|exists:roles,id',
            ]);

            if($validator->fails()){
                $errors = $validator->errors();
                if ($errors->has('email') && $errors->has('mobile'))
                    $code = 5031;
                elseif($errors->has('email') && !$errors->has('mobile'))
                    $code = 5032;
                elseif(!$errors->has('email') && $errors->has('mobile'))
                    $code = 5033;
                elseif($errors->has('photo'))
                    $code = 5034;
                else
                    $code = 5030;
                return $this->sendError($validator->errors()->first(), $code);
            }

            $to_save = [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'mobile' => $request->get('mobile'),
                'human_job_id' => $request->get('human_job_id'),
            ];

            if($request->password && ! empty($request->password))
            {
                $to_save['password'] = Hash::make($request->password);
            }


            if($request->income && ! empty($request->income))
            {
                $to_save['income'] = $request->income;
            }
            if($request->dob && ! empty($request->dob))
            {
                $to_save['dob'] = $request->dob;
            }
            if($request->city_id && ! empty($request->city_id))
            {
                $to_save['city_id'] = $request->city_id;
            }


            DB::beginTransaction();
            $user = $this->userRepository->update($to_save, $id);

            if (isset($request->roles))
            {
                if(auth()->user()->hasRole(config('myconfig.admin_role'))) // because the current method (update) is for both admins and users
                {
                    $admin_allowed_roles = Role::appAllowedRoles()->pluck('id')->toArray();

                    if (array_diff($request->roles,$admin_allowed_roles))
                    {
                        return $this->sendError('Not allowed roles');
                    }
                    $user->syncRoles($request->roles);
                }
            }

            if($photo = $request->file('photo'))
            {
                if($user->asset)
                    $user->asset->delete();
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo);
                $user->asset()->create($oneasset);
                $user->load('asset');
            }

            DB::commit();



            return $this->sendResponse(new UserResource($user), __('Success'));
        }
        catch(\Throwable $th)
        {
            DB::rollback();
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function update_profile(UpdateProfileAPIRequest $request)
    {
        try
        {
            $user = auth()->user();
            $input = $request->validated();
            DB::beginTransaction();
            $user = $this->userRepository->update($input, $user->id);
            $arrays = ['educations', 'careers', 'residences', 'visiteds'];
            foreach($arrays as $prop){
                $user->$prop()->delete();
                foreach($request->$prop ?? [] as $one){
                    $user->$prop()->create($one);
                }
            }

            if($photo = $request->file('photo'))
            {
                if($user->asset)
                    $user->asset->delete();
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo);
                $user->asset()->create($oneasset);
                $user->load('asset');
            }
            DB::commit();
            return $this->sendResponse(new UserProfileResource($user), __('Success'));
        }
        catch(\Throwable $th)
        {
            DB::rollback();
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function update_password($id, Request $request)
    {
        try
        {
            $user = $this->userRepository->find($id);

            if (empty($user)) {
                return $this->sendError('User not found');
            }

            $old_password_required = $user->password ? 'required' : 'nullable';

            $validator = Validator::make($request->all(), [
                'old_password' => $old_password_required . '|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors()->first());
            }
            if($old_password_required == 'required'){
                if(! password_verify($request->old_password, $user->password))
                {
                    return $this->sendError('Incorrect old password');
                }

                if(password_verify($request->password, $user->password))
                {
                    return $this->sendError('The new password is identical to the old password');
                }
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return $this->sendSuccess(__('Password updated successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try
        {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }
        $return = [
            'posts' => $user->posts,
            'products' => $user->products,
            'reports' => $user->reports,
            'farms' => $user->farms,
        ];
        // return $return;

        $user->posts()->delete();// comments/likes/ chemical/location/saltdetails
        $user->products()->delete();
        $user->reports()->delete();
        $user->favorites()->delete();
        $user->farms()->update(['admin_id' => auth()->id()]);

        $user->delete();
        $user->asset->delete();

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
