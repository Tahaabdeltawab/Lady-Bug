<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Requests\API\CreateUserFavoritesAPIRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Post;
use App\Models\Product;
use App\Models\ServiceTask;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\FarmResource;
use App\Http\Resources\FarmWithServiceTasksReource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\ServiceTaskResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\FarmedTypeGinfoResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\HumanJobRepository;
use App\Repositories\AssetRepository;
use App\Repositories\ServiceTaskRepository;
use App\Repositories\ProductRepository;
use App\Repositories\FarmedTypeGinfoRepository;

use App\Http\Helpers\WeatherApi;
use App\Http\Resources\FarmCollection;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    private $humanJobRepository;
    private $assetRepository;
    private $farmedTypeRepository;
    private $serviceTaskRepository;
    private $productRepository;
    private $farmedTypeGinfoRepository;

    public function __construct(
        HumanJobRepository $humanJobRepo,
        AssetRepository $assetRepo,
        UserRepository $userRepo,
        FarmedTypeRepository $farmedTypeRepo,
        ServiceTaskRepository $serviceTaskRepo,
        ProductRepository $productRepo,
        FarmedTypeGinfoRepository $farmedTypeGinfoRepo
        )
    {
        $this->userRepository = $userRepo;
        $this->humanJobRepository = $humanJobRepo;
        $this->assetRepository = $assetRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->serviceTaskRepository = $serviceTaskRepo;
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
        $users = User::where('name','like', '%'.$query.'%' )->orWhere('email','like', '%'.$query.'%')->orWhere('mobile','like', '%'.$query.'%')->get();
        return $this->sendResponse(['all' => UserResource::collection($users)], 'Users retrieved successfully');
    }


    public function user_farms(Request $request)
    {
        try{
            $weather_resp = WeatherApi::instance()->weather_api($request);
            $weather_data = $weather_resp['data'];
            $user = auth()->user();
            $farms = $user->allTeams()->where('archived', false);
            return $this->sendResponse([
                'unread_notifications_count' => $user->unreadNotifications->count(),
                'weather_data' => $weather_data,
                'farms' => FarmResource::collection($farms)
            ], 'Farms retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function user_today_tasks(Request $request)
    {
        try{
            $weather_resp = WeatherApi::instance()->weather_api($request);
            $weather_data = $weather_resp['data'];

            $farms = auth()->user()->rolesTeams()->with('service_tasks', function($query){
                $query->where('start_at', date('Y-m-d'));
            })->whereHas('service_tasks', function($q){
                $q->where('start_at', date('Y-m-d'));
            })->get();

            return $this->sendResponse([
                'weather_data' => $weather_data,
                'tasks' => FarmWithServiceTasksReource::collection($farms)
            ], 'Today\'s tasks retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function user_products(Request $request)
    {
        try{
            $products = $this->productRepository->where(['seller_id' => auth()->id()])->all();

            return $this->sendResponse(['all' => ProductResource::collection($products)], 'User products retrieved successfully');
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

        $fav_products = Product::whereIn('farmed_type_id', $fav_farmed_types_ids)->limit(10)->get();
        $latest_products = Product::latest()->limit(10)->get();

        return $this->sendResponse(
            [
                'weather_data' => $weather_data,
                'favourites' => FarmedTypeResource::collection($favorites),
                'farmed_type_ginfos' => FarmedTypeGinfoResource::collection($fav_farmed_type_ginfos),
                'favorite_products' => ProductResource::collection($fav_products),
                'latest_products' => ProductResource::collection($latest_products)
            ], 'Farmed Type General Information relations retrieved successfully');
    }




// // // // // // NOTIFICATIONS // // // // // //

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
            }
            return $this->sendError(__('Your Notifications are off, turn them on to see your notifications.'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
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





    // // // // // //  POSTS  // // // // // //

    public function user_posts()
    {
        try
        {
            $posts = auth()->user()->posts()->accepted()->get();
            return $this->sendResponse(['all' => PostResource::collection($posts)], 'User posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
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




    // // // // FOLLOW // // // //

    public function toggleFollow($id)
    {
        try
        {
            $user = $this->userRepository->find($id);

            if (empty($user))
            {
                return $this->sendError('User not found');
            }

            if(auth()->user()->isFollowing($user))
            {
                auth()->user()->unfollow($user);
                return $this->sendSuccess("You have unfollowed $user->name successfully");
            }
            else
            {
                auth()->user()->follow($user);
                if($user->is_notifiable)
                $user->notify(new \App\Notifications\Follow(auth()->user()));
                return $this->sendSuccess("You have followed $user->name successfully");
            }
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function my_followers()
    {
        $my_followers = auth()->user()->followers;

        return $this->sendResponse(['count' => $my_followers->count(), 'all' => UserResource::collection($my_followers)], 'User followers retrieved successfully');
    }

    public function my_followings()
    {
        $my_followings = auth()->user()->followings;

        return $this->sendResponse(['count' => $my_followings->count(), 'all' => UserResource::collection($my_followings)], 'User followings retrieved successfully');
    }




    // // // // // RATE // // // //

    public function rate(Request $request)
    {
        try
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'rating' => ['required', 'numeric', 'max:5', 'min:1'],
                    'user' => ['required', 'integer', 'exists:users,id']
                ]
            );

            if($validator->fails()){
                return $this->sendError(json_encode($validator->errors()), 422);
            }

            $user = $this->userRepository->find($request->user);

            if (empty($user))
            {
                return $this->sendError('User not found');
            }
                $user->rateOnce($request->rating);
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
                'name' => ['required', 'string', 'max:255'],
                "email" => ["required", "string", "email", "max:255", "unique:users,email,".null.",id"],
                "mobile" => ["required", "string", "max:255", "unique:users,mobile,".null.",id"],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'human_job_id' => ['nullable', 'exists:human_jobs,id'],
                'photo' => ['nullable', 'max:2000', 'image', 'mimes:jpeg,jpg,png'],
                'roles'   => ['nullable', 'array'],
                'roles.*' => ['nullable', 'exists:roles,id'],
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
                return $this->sendError(json_encode($validator->errors()), $code);
            }

            DB::beginTransaction();
            $user = $this->userRepository->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'type'  => "app_admin",
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
                $currentDate = Carbon::now()->toDateString();
                $photoname = 'profile-'.$currentDate.'-'.uniqid().'.'.$photo->getClientOriginalExtension();
                $photosize = $photo->getSize(); //size in bytes 1k = 1000bytes
                $photomime = $photo->getClientMimeType();

                $path = $photo->storeAs('assets/images/profiles', $photoname, 's3');
                // $path = Storage::disk('s3')->putFileAs('photos/images', $photo, $photoname);

                $url  = Storage::disk('s3')->url($path);

                $asset = $user->asset()->create([
                    'asset_name'        => $photoname,
                    'asset_url'         => $url,
                    'asset_size'        => $photosize,
                    'asset_mime'        => $photomime,
                ]);
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
                return $this->sendError(json_encode($validator->errors()));
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
                $validator = Validator::make(request()->all(), [ 'blocked_until' => 'nullable|date_format:Y-m-d', 'block_days' => 'nullable|integer']);
                if($validator->fails())
                    return $this->sendError(json_encode($validator->errors()), 757);
    
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
                "name" => ["required", "string", "max:255"],
                "email" => ["required", "string", "email", "max:255", "unique:users,email,$id,id"],
                "mobile" => ["required", "string", "max:255", "unique:users,mobile,$id,id"],
                "password" => ["nullable", "string", "min:8", "confirmed"],
                "human_job_id" => ["nullable", "exists:human_jobs,id"],
                "income" => ["nullable", "integer", "min:0"],
                "dob" => ["nullable", "date_format:Y-m-d"],
                "city_id" => ["nullable", "exists:cities,id"],
                "photo" => ["nullable", "max:2000", "mimes:jpeg,jpg,png"],
                'roles'   => ['nullable', 'array'],
                'roles.*' => ['nullable', 'exists:roles,id'],
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
                return $this->sendError(json_encode($validator->errors()), $code);
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
            $user = $this->userRepository->save_localized($to_save, $id);

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
                $currentDate = Carbon::now()->toDateString();
                $photoname = 'profile-'.$currentDate.'-'.uniqid().'.'.$photo->getClientOriginalExtension();
                $photosize = $photo->getSize(); //size in bytes 1k = 1000bytes
                $photomime = $photo->getClientMimeType();

                $path = $photo->storeAs('assets/images/profiles', $photoname, 's3');
                // $path = Storage::disk('s3')->putFileAs('photos/images', $photo, $photoname);

                $url  = Storage::disk('s3')->url($path);

                $user->asset()->delete();
                $asset = $user->asset()->create([
                    'asset_name'        => $photoname,
                    'asset_url'         => $url,
                    'asset_size'        => $photosize,
                    'asset_mime'        => $photomime,
                ]);
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
                "old_password" => [$old_password_required, "string"],
                "password" => ["required", "string", "min:8", "confirmed"],
            ]);

            if($validator->fails()){
                return $this->sendError(json_encode($validator->errors()));
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
        $path = parse_url($user->asset->asset_url, PHP_URL_PATH);
        Storage::disk('s3')->delete($path);
        $user->asset()->delete();

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
