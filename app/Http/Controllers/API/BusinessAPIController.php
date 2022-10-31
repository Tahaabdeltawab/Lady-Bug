<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessAPIRequest;
use App\Http\Requests\API\UpdateBusinessAPIRequest;
use App\Models\Business;
use App\Repositories\BusinessRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Helpers\WeatherApi;
use App\Http\Requests\API\RateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Http\Resources\BusinessWithPostsResource;
use App\Http\Resources\BusinessWithTasksResource;
use App\Http\Resources\FarmXsResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\PostXsResource;
use App\Http\Resources\ProductXsResource;
use App\Http\Resources\RoleSmResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserConsXsResource;
use App\Http\Resources\UserOfBusinessResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserXsResource;
use App\Models\BusinessConsultant;
use App\Models\BusinessField;
use App\Models\ConsultancyProfile;
use App\Models\OfflineConsultancyPlan;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Response;

/**
 * Class BusinessController
 * @package App\Http\Controllers\API
 */

class BusinessAPIController extends AppBaseController
{
    /** @var  BusinessRepository */
    private $businessRepository;
    public function __construct(BusinessRepository $businessRepo)
    {
        $this->businessRepository = $businessRepo;
    }

    /**
     * Display a listing of the Business.
     * GET|HEAD /businesses
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $businesses = $this->businessRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BusinessResource::collection($businesses), 'Businesses retrieved successfully');
    }


    public function rate_business(RateBusinessRequest $request)
    {
        try
        {
            $business = $this->businessRepository->find($request->business);
            $business->rateOnce($request->rating);
            return $this->sendSuccess("You have rated $business->com_name with $request->rating stars successfully");
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function get_business_with_posts($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('business not found');
            return $this->sendResponse(new BusinessWithPostsResource($business), 'business with posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function get_business_posts($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('business not found');
            $posts = $business->posts()->accepted()->notVideo()->get();
            return $this->sendResponse(PostXsResource::collection($posts), 'business posts retrieved successfully');
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
            $videos = $business->posts()->accepted()->video()->get();
            return $this->sendResponse(PostXsResource::collection($videos), 'business videos retrieved successfully');
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
            $videos = $business->posts()->accepted()->video()->get();
            return $this->sendResponse(PostXsResource::collection($videos), 'business stories retrieved successfully');
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
            $products = $business->products;
            return $this->sendResponse(ProductXsResource::collection($products), 'business products retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function get_business_farms($id)
    {
         /** @var Business $business */
         $business = $this->businessRepository->find($id);

         if (empty($business))
             return $this->sendError('Business not found');
        $farms = $business->farms;
         return $this->sendResponse(FarmXsResource::collection($farms), 'Business farms retrieved successfully');
    }


    /**
     * Store a newly created Business in storage.
     * POST /businesses
     *
     * @param CreateBusinessAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBusinessAPIRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->validated();
            $input['user_id'] = auth()->id();

            $business = $this->businessRepository->create($input);

            auth()->user()->attachRole('business-admin', $business);
            auth()->user()->attachPermissions(Permission::businessAllowed()->pluck('id'), $business);
            $business->agents()->attach($request->agents, ['type' => 'agent']);
            $business->distributors()->attach($request->distributors, ['type' => 'distributor']);
            foreach ($request->branches ?? [] as $branch) {
                $business->branches()->create($branch);
            }

            if($main_asset = $request->file('main_asset'))
            {
                $asset = $this->store_file($main_asset, 'business-main');
                $business->assets()->create($asset);
            }

            if($cover_asset = $request->file('cover_asset'))
            {
                $asset = $this->store_file($cover_asset, 'business-cover');
                $business->assets()->create($asset);
            }

            DB::commit();
            return $this->sendResponse(new BusinessResource($business), 'Business saved successfully');
        } catch(\Throwable $th){
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }



    public function get_business_users($id)
    {
        try
        {
            $slcts = User::$selects;
            foreach (config('myconfig.business_roles') as $role) {
                $role_id = Role::where('name', $role)->value('id');
                $us = User::join('role_user', 'users.id', 'role_user.user_id')
                ->where('business_id', $id)
                ->where('role_id', $role_id)
                ->get(['role_user.start_date', 'role_user.end_date', ...$slcts]);
                $users[\Str::camel($role)] = UserOfBusinessResource::collection($us);
            }

            return $this->sendResponse($users, 'business users retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function business_roles()
    {
        $roles = Role::businessAllowedRoles()->get();
        return $this->sendResponse(['all' =>  RoleSmResource::collection($roles)], 'Roles retrieved successfully');
    }

    public function business_permissions()
    {
        $permissions = Permission::businessAllowed()->get();
        return $this->sendResponse(['all' =>  PermissionResource::collection($permissions)], 'Permissions retrieved successfully');
    }


    /**
     * users options to assign them one of my business roles
     */
    public function app_users(Request $request)
    {
        $business = Business::find($request->business);
        if (empty($business))
            return $this->sendError('business not found');

        $business_users = $business->users()->pluck('users.id');
        $business_users[] = auth()->id();
        $users = User::whereNotIn('users.id', $business_users)->whereHas('roles', function($q){
            $q->where('name', config('myconfig.user_default_role'));
        })
        ->when($request->cons, fn ($q) => $q->cons())
        ->get(['email', 'is_consultant', ...User::$selects]);

        return $this->sendResponse(['all' => $request->cons ? UserConsXsResource::collection($users) : UserXsResource::collection($users)], 'Users retrieved successfully');
    }

    public function search_cons(Request $request)
    {
        $business = Business::find($request->business);
        if (empty($business))
            return $this->sendError('business not found');

        $business_users = $business->users()->pluck('users.id');
        $business_users[] = auth()->id();
        $users = User::whereNotIn('users.id', $business_users)
        ->whereHas('roles', function($q){
            $q->where('name', config('myconfig.user_default_role'));
        });
        if(request()->by_name)
            $users = $users->when(request()->name, fn ($q) => $q->where('name', 'like', "%".request()->name."%"))
                           ->when(request()->mobile, fn ($q) => $q->where('mobile', 'like', "%".request()->mobile."%"));
        else
        $users = $users->whereHas('consultancyProfile', function($q){
            $q
            ->when(request()->ar, fn ($qq) => $qq->where('ar', 1))
            ->when(request()->en, fn ($qq) => $qq->where('en', 1))
            ->when(request()->experience, fn ($qq) => $qq->where('experience', request()->experience))
            ->when(request()->consultancy_price, fn ($qq) => $qq->whereBetween('consultancy_price', request()->consultancy_price))
            ->when(request()->year_consultancy_price, fn ($qq) => $qq->whereBetween('year_consultancy_price', request()->year_consultancy_price))
            ->when(request()->work_fields,fn ($qq) => $qq->whereHas('workFields', function($q){
                $q->whereIn('work_fields.id', request()->work_fields);
            }))
            ;
        });
        // ->when(request()->cities, fn ($qq) => $qq->whereIn('city_id', request()->cities)) // not present in user nor consultancy registration


        $users = $users->cons()->get(['email', 'is_consultant', ...User::$selects]);
        if(request()->by_name && count($users) == 0)
            return $this->sendError(__('Consultant not found, please refer him to register in our app.'));
        return $this->sendResponse(['all' => UserConsXsResource::collection($users)], 'Users retrieved successfully');
    }

    private function is_valid_invitation($request){
        $invitation = DB::table('notifications')
        ->where('type', 'App\Notifications\BusinessInvitation')
        ->where('notifiable_type', 'App\Models\User')
        ->where('notifiable_id', $request->user)
        ->where('data->invitee', $request->user)
        ->where('data->business', $request->business)
        ->where('data->role', $request->role)
        ->select(['data'])
        ->latest()
        ->first();

        if( ! $invitation )
        return false;

        $accepted = json_decode($invitation->data)->accepted;
        if($accepted !== null ) // if the $accepted = null means that it was not accepted nor declined
        {
           return false;
        }

        return true;
    }


        // attach a business role to a user who has an invitation link
    public function first_attach_business_role(Request $request)
    {
        try
        {
            if ( ! $request->hasValidSignature() || !$request->user || !$request->role || !$request->business )
            return $this->sendError('Wrong url', 401);

            if( ! $this->is_valid_invitation($request) )
            return $this->sendError('Wrong Invitation', 403);

            if( auth()->id() != $request->user )
            return $this->sendError('You are not the invited person', 403);

            $user = User::find($request->user);
            $business = Business::find($request->business);

            if($request->role == 6 && !$request->period)
                return $this->sendError('Consultancy Period is required');
            $user->attachRole($request->role, $business);
            $user->attachPermissions($request->permissions, $business);
            $role_user = RoleUser::where(['user_id' => $request->user, 'role_id' => $request->role, 'business_id' => $request->business])->first();
            $role_user->start_date = $request->start_date;
            $role_user->end_date = $request->end_date;
            $role_user->save();
            // إضافة استشاري
            if($request->role == 6){
                BusinessConsultant::create([
                    'role_user_id' => $role_user->id,
                    'plan_id' => $request->plan_id,
                    'period' => $request->period,
                ]);

                if($request->plan_id)
                    $price = OfflineConsultancyPlan::where('id', $request->plan_id)->value($request->period);
                else{
                    $cp = ConsultancyProfile::where('user_id', $request->user)->first();
                    $price = $cp->{$request->period};
                }
                Transaction::create([
                    'type' => 'out',
                    'user_id' => auth()->id(),
                    'gateway' => '',
                    'total' => $price,
                    'description' => "Add consultant $cp->id with period $request->period $request->plan_id"
                ]);
                auth()->user()->balance -= $price;
                auth()->user()->save();

            }


            DB::table('notifications')
            ->where('type', 'App\Notifications\BusinessInvitation')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $request->user)
            ->where('data->invitee', $request->user)
            ->where('data->business', $request->business)
            ->where('data->role', $request->role)
            ->update(['data->accepted' => true]);

            return $this->sendResponse(new UserResource($user), __('Member added to business successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    // decline business invitation
    public function decline_business_invitation(Request $request)
    {
        try
        {
            if (! $request->hasValidSignature() || !$request->user || !$request->role || !$request->business) {
                return $this->sendError('Wrong url', 401);
            }

            if(!$this->is_valid_invitation($request))
            return $this->sendError('Wrong Invitation', 403);

            if( auth()->id() != $request->user )
            return $this->sendError('You are not the invited person', 403);

            DB::table('notifications')
            ->where('type', 'App\Notifications\BusinessInvitation')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $request->user)
            ->where('data->invitee', $request->user)
            ->where('data->business', $request->business)
            ->where('data->role', $request->role)
            ->update(['data->accepted' => false]);

            return $this->sendSuccess(__('Invitation declined'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    //attach, edit or delete business roles (send empty or no role_id when deleting a role)
    public function update_business_role(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'business' => 'required|exists:businesses,id',
                'user' => 'required|exists:users,id',
                'role' => 'nullable|exists:roles,id',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
                'period' => 'nullable',
                'plan_id' => 'nullable|exists:offline_consultancy_plans,id',
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $user = User::find($request->user);
            $business = Business::find($request->business);

            // if(auth()->id() != $business->user_id)
            if(!auth()->user()->hasPermission("edit-role", $business))
                return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

            if($request->role)   //first attach or edit roles
            {
                $role = Role::find($request->role);
                if(!in_array($role->name, config('myconfig.business_roles')))
                    return $this->sendError('Invalid Role');

                if($user->get_roles($request->business)) //edit roles
                {
                    if($request->role == 6 && !$request->period)
                        return $this->sendError('Consultancy Period is required');
                    $user->syncRoles([$request->role], $business);
                    $user->syncPermissions($request->permissions, $business);
                    $role_user = RoleUser::where(['user_id' => $request->user, 'role_id' => $request->role, 'business_id' => $request->business])->first();
                    $role_user->start_date = $request->start_date;
                    $role_user->end_date = $request->end_date;
                    $role_user->save();
                    // إضافة استشاري
                    if($request->role == 6){
                        BusinessConsultant::create([
                            'role_user_id' => $role_user->id,
                            'plan_id' => $request->plan_id,
                            'period' => $request->period,
                        ]);

                        $cp = ConsultancyProfile::where('user_id', $request->user)->first();
                        if($request->plan_id)
                            $price = OfflineConsultancyPlan::where('id', $request->plan_id)->value($request->period);
                        else
                            $price = $cp->{$request->period};

                        Transaction::create([
                            'type' => 'out',
                            'user_id' => auth()->id(),
                            'gateway' => '',
                            'total' => $price,
                            'description' => "Add consultant $cp->id with period $request->period $request->plan_id"
                        ]);
                        auth()->user()->balance -= $price;
                        auth()->user()->save();

                        $cp->user->notify(new \App\Notifications\ConsBought($price));

                    }
                }
                else            // first attach role
                {
                    //send invitation to assignee user
                    if($user->is_notifiable){

                    $user->notify(new \App\Notifications\BusinessInvitation(
                        auth()->user(),
                        $role,
                        $business,
                        URL::temporarySignedRoute('api.businesses.roles.first_attach', now()->addDays(30),[
                            'user' => $request->user, 'business' => $request->business, 'role' => $request->role,
                            'start_date' => $request->start_date, 'end_date' => $request->end_date, 'period' => $request->period,
                            'plan_id' => $request->plan_id, 'permissions' => $request->permissions]),
                        URL::temporarySignedRoute('api.businesses.roles.decline_business_invitation', now()->addDays(30),[
                            'user' => $request->user, 'business' => $request->business, 'role' => $request->role])
                        ));
                    return $this->sendSuccess(__('Invitation sent successfully'));
                    }else{
                        return $this->sendSuccess(__('Invitation could not be sent because the user notifications are off'));
                    }
                }

            }
            else                    //delete roles
            {
                if($user->get_roles($request->business)){
                    $user->syncRoles([], $business);
                    $user->syncPermissions([], $business);
                }else{
                    return $this->sendError(__('This user is not a member in this business'), 7000);
                }
            }

            $slcts = User::$selects;
            $us = User::join('role_user', 'users.id', 'role_user.user_id')
                ->where('business_id', $request->business)
                ->where('role_id', $request->role)
                ->where('user_id', $request->user)
                ->select(['role_user.start_date', 'role_user.end_date', ...$slcts])
                ->first();

            DB::commit();
            return $this->sendResponse(new UserOfBusinessResource($us), __('business roles saved successfully'));
        }
        catch(\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function user_businesses(Request $request)
    {
        try{
            $weather_resp = WeatherApi::instance()->weather_api($request);
            $weather_data = $weather_resp['data'];
            $user = auth()->user();
            $own_businesses = $user->ownBusinesses;
            $shared_businesses = $user->sharedBusinesses($own_businesses->pluck('id'))->get();
            $followed_businesses = $user->followedBusinesses;
            return $this->sendResponse([
                'unread_notifications_count' => $user->unreadNotifications->count(),
                'weather_data' => $weather_data,
                'own_businesses' => BusinessResource::collection($own_businesses),
                'shared_businesses' => BusinessResource::collection($shared_businesses),
                'followed_businesses' => BusinessResource::collection($followed_businesses)
            ], 'businesses retrieved successfully');
        }catch(\Throwable $th){
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function report_tasks($id)
    {
        $tasks = Task::where('farm_report_id', $id)->get();
        return $this->sendResponse(TaskResource::collection($tasks), 'tasks retrived successfully');
    }

    public function user_today_tasks(Request $request)
    {
        try{
            $date = $request->date ?? date('Y-m-d');

            if($request->tasks_only){
                $tasks = auth()->user()->tasks()->where('farm_report_id', $request->report_id)->where('date', $date)->get();
                return $this->sendResponse(TaskResource::collection($tasks), 'tasks retrived successfully');
            }
            $weather_resp = WeatherApi::instance()->weather_api($request);
            $weather_data = $weather_resp['data'];

            $businesses = auth()->user()->allBusinesses()->with('tasks', function($query) use($date){
                $query->where('date', $date);
            })->whereHas('tasks', function($q) use($date){
                $q->where('date', $date);
            })->get();

            return $this->sendResponse([
                'weather_data' => $weather_data,
                'tasks' => BusinessWithTasksResource::collection($businesses)
            ], 'tasks retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    // FOLLOW
    public function toggle_follow($id)
    {
        try
        {
            $business = Business::find($id);
            if (empty($business))
                return $this->sendError('Business not found');

            if(auth()->user()->isFollowing($business))
            {
                auth()->user()->unfollow($business);
                return $this->sendSuccess(__('unfollowed', ['name' => $business->com_name]));
            }
            else
            {
                auth()->user()->follow($business);
                return $this->sendSuccess(__('followed', ['name' => $business->com_name]));
            }
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    /**
     * Display the specified Business.
     * GET|HEAD /businesses/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Business $business */
        $business = $this->businessRepository->find($id);

        if (empty($business)) {
            return $this->sendError('Business not found');
        }

        return $this->sendResponse(new BusinessResource($business), 'Business retrieved successfully');
    }

    public function statuses($id = null)
    {
        $statuses =  [
            ['id' => 0, 'name' => app()->getLocale() == 'ar' ?  'لم يبدأ بعد' : 'have not started'],
            ['id' => 1, 'name' => app()->getLocale() == 'ar' ?  'تحت الإنشاء' : 'Under Construction'],
            ['id' => 2, 'name' => app()->getLocale() == 'ar' ?  'يمارس نشاطه' : 'Working']
        ];
        if($id){
            return collect($statuses)->firstWhere('id', $id);
        }else
            return $statuses;
    }

    public function getRelations($business_field_id = null)
    {
        if($business_field_id == null){
            return $this->sendResponse([
                'business_fields' => BusinessField::all()
            ],
            'business fields retrieved successfully'
            );
        }
        $similar_dealers = Business::select('id', 'com_name')->where('business_field_id', $business_field_id)->get();
        return $this->sendResponse([
            'agents' => $similar_dealers,
            'distributors' => $similar_dealers,
            'statuses' => $this->statuses()
        ], 'business relations retrieved successfully');
    }

    public function update($id, UpdateBusinessAPIRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->validated();

            /** @var Business $business */
            $business = $this->businessRepository->find($id);

            if (empty($business)) {
                return $this->sendError('Business not found');
            }

            if(!auth()->user()->hasPermission("edit-business", $business))
                return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

            $business = $this->businessRepository->update($input, $id);

            $business->agents()->detach();
            $business->agents()->attach($request->agents, ['type' => 'agent']);

            $business->distributors()->detach();
            $business->distributors()->attach($request->distributors, ['type' => 'distributor']);

            $business->branches()->delete();
            foreach ($request->branches ?? [] as $branch) {
                $business->branches()->create($branch);
            }

            if($main_asset = $request->file('main_asset'))
            {
                foreach ($business->main_asset as $ass) {
                    $ass->delete();
                }
                $asset = $this->store_file($main_asset, 'business-main');
                $business->assets()->create($asset);
            }

            if($cover_asset = $request->file('cover_asset'))
            {
                foreach ($business->cover_asset as $ass) {
                    $ass->delete();
                }
                $asset = $this->store_file($cover_asset, 'business-cover');
                $business->assets()->create($asset);
            }

            DB::commit();
            return $this->sendResponse(new BusinessResource($business), 'Business updated successfully');
        } catch(\Throwable $th){
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Remove the specified Business from storage.
     * DELETE /businesses/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
        /** @var Business $business */
        $business = $this->businessRepository->find($id);

        if (empty($business))
            return $this->sendError('Business not found');


        if(auth()->id() != $business->user_id)
            return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

        DB::beginTransaction();

        foreach($business->assets as $ass){
            $ass->delete();
        }
        foreach($business->posts as $post){
            foreach($post->assets as $ass){
                $ass->delete();
             }
             $post->delete();
        }
        foreach($business->products as $product){
            foreach ($product->ads as $delad) {
                $delad->asset->delete();
                $delad->delete();
            }
            foreach($product->assets as $ass){
                $ass->delete();
            }
            $product->delete();
        }
        foreach($business->farms as $farm){
            $farm->location()->delete();
            $farm->soil_detail->delete();
            $farm->soil_detail->salt_detail()->delete();
            $farm->irrigation_water_detail->delete();
            $farm->irrigation_water_detail->salt_detail()->delete();
            $farm->animal_drink_water_salt_detail()->delete();
            foreach($farm->farm_reports as $report){
                $report->tasks()->delete();
                $report->delete();
            }
            $farm->delete();
        }
        $business->branches()->delete();
        $business->parts()->delete();
        DB::table('business_dealer')->where('business_id', $business->id)->delete();
        DB::table('role_user')->where('business_id', $business->id)->delete();
        DB::table('permission_user')->where('business_id', $business->id)->delete();
        $business->delete();
        DB::commit();
        return $this->sendSuccess('Business deleted successfully');
        }
        catch(\Throwable $th)
        {
            DB::rollBack();
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }


    public function store_file($file, $type = 'profile'){
        $currentDate = Carbon::now()->toDateString();
        $imagename = $type.'-'.$currentDate.'-'.uniqid().'.'.$file->getClientOriginalExtension();
        $imagesize = $file->getSize(); //size in bytes 1k = 1000bytes
        $imagemime = $file->getClientMimeType();
        $path = $file->storeAs('assets/'.$type, $imagename, 's3');
        $url  = Storage::disk('s3')->url($path);
        return [
            'asset_name'        => $imagename,
            'asset_url'         => $url,
            'asset_size'        => $imagesize,
            'asset_mime'        => $imagemime,
            'asset_path'        => $path,
        ];
    }

    public function delete_files($paths){
        Storage::disk('s3')->delete($paths);
    }
}
