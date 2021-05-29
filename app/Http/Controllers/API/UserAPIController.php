<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Requests\API\CreateUserFavoritesAPIRequest;
use App\Models\User;
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
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users",
     *      summary="Get a listing of the Users.",
     *      tags={"User"},
     *      description="Get all Users",
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
     *                  @SWG\Items(ref="#/definitions/User")
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
        $users = $this->userRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

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
                $user->unreadNotifications->markAsRead();
                $notifications = $user->notifications ;

                return $this->sendResponse(['all' => NotificationResource::collection($notifications)], 'Notifications retrieved successfully');
            }
            return $this->sendError(__('Your Notifications are off, turn them all to see your notifications.'));
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
            $posts = auth()->user()->posts;
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
            return $this->sendResponse(['all' => PostResource::collection($likeables)], 'User liked posts retrieved successfully');
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
            return $this->sendResponse(['all' => PostResource::collection($dislikeables)], 'User disliked posts retrieved successfully');
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


    /**
     * @param CreateUserAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users",
     *      summary="Store a newly created User in storage",
     *      tags={"User"},
     *      description="Store User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->validated();

        $user = $this->userRepository->save_localized($input);

        return $this->sendResponse(new UserResource($user), 'User saved successfully');
    }


    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/{id}",
     *      summary="Display the specified User",
     *      tags={"User"},
     *      description="Get User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
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
     *                  ref="#/definitions/User"
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
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateUserAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/users/{id}",
     *      summary="Update the specified User in storage",
     *      tags={"User"},
     *      description="Update User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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
                "human_job_id" => ["required", "exists:human_jobs,id"],
                "photo" => ["nullable", "max:2000", "mimes:jpeg,jpg,png"],
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

            if($request->password)
            {
                $to_save['password'] = Hash::make($request->password);
            }

            $user = $this->userRepository->save_localized($to_save, $id);

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



            // $this->roleRepository->setRoleToMember($user, $userDefaultRole);
            return $this->sendResponse(new UserResource($user), __('Success'));
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
     *      path="/users/{id}",
     *      summary="Remove the specified User from storage",
     *      tags={"User"},
     *      description="Delete User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
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
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendSuccess('User deleted successfully');
    }
}
