<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessAPIRequest;
use App\Http\Requests\API\UpdateBusinessAPIRequest;
use App\Models\Business;
use App\Repositories\BusinessRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Helpers\WeatherApi;
use App\Http\Resources\BusinessResource;
use App\Http\Resources\BusinessWithTasksResource;
use App\Http\Resources\FarmResource;
use App\Http\Resources\PostXsResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\BusinessField;
use App\Models\Role;
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


       

    public function get_business_posts($id)
    {
        try
        {
            $business = Business::find($id);

            if (empty($business))
            {
                return $this->sendError('business not found');
            }

            $posts = $business->posts()->accepted()->get();
            return $this->sendResponse(['all' => PostXsResource::collection($posts)], 'business posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
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
            $business = Business::find($id);

            if (empty($business))
            {
                return $this->sendError('business not found');
            }

            $users = $business->users;
            return $this->sendResponse(['all' => UserResource::collection($users)], 'business users retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function app_roles(Request $request)
    {
        $roles = Role::businessAllowedRoles()->get();
        return $this->sendResponse(['all' =>  RoleResource::collection($roles)], 'Roles retrieved successfully');
    }


    /**
     * users options to assign them one of my business roles
     */
    public function app_users(Request $request)
    {
        $business = Business::find($request->business);
        if (empty($business))
        {
            return $this->sendError('business not found');
        }

        $business_users = $business->users->pluck('id');
        $business_users[] = auth()->id();
        $users = User::whereNotIn('id', $business_users)->whereHas('roles', function($q){
            $q->where('name', config('myconfig.user_default_role'));
        })->get();

        return $this->sendResponse(['all' => UserResource::collection($users)], 'Users retrieved successfully');
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

            $user->attachRole($request->role, $business);
            DB::table('role_user')->where(['user_id' => $request->user, 'role_id' => $request->role, 'business_id' => $request->business])
            ->update(['start_date' => $request->start_date, 'end_date' => $request->end_date]);

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
            $validator = Validator::make($request->all(), [
                'business' => 'integer|required|exists:businesses,id',
                'user' => 'integer|required|exists:users,id',
                'role' => 'nullable|integer|exists:roles,id',
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return $this->sendError(json_encode($validator->errors()));
            }

            $user = User::find($request->user);
            $business = Business::find($request->business);
           
            if(auth()->id() != $business->user_id)
            abort(503, __('Unauthorized, you are not the business owner!'));

            if($request->role)   //first attach or edit roles
            {
                $role = Role::find($request->role);
                if(!in_array($role->name, config('myconfig.business_allowed_roles')))
                {
                    return $this->sendError('Invalid Role');
                }

                if($user->get_roles($request->business)) //edit roles
                {
                    $user->syncRoles([$request->role], $business);
                    DB::table('role_user')->where(['user_id' => $request->user, 'role_id' => $request->role, 'business_id' => $request->business])
                    ->update(['start_date' => $request->start_date, 'end_date' => $request->end_date]);
                }
                else            // first attach role
                {
                    //send invitation to assignee user
                    if($user->is_notifiable){

                    $user->notify(new \App\Notifications\BusinessInvitation(
                        auth()->user(),
                        $role,
                        $business,
                        URL::temporarySignedRoute('api.businesses.roles.first_attach', now()->addDays(30),['user' => $request->user,'business' => $request->business,'role' => $request->role,'start_date' => $request->start_date, 'end_date' => $request->end_date]),
                        URL::temporarySignedRoute('api.businesses.roles.decline_business_invitation', now()->addDays(30),['user' => $request->user,'business' => $request->business,'role' => $request->role,])
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
                    $user->detachRoles([], $business);
                }else{
                    return $this->sendError(__('This user is not a member in this business'), 7000);
                }
            }

            return $this->sendResponse(new UserResource($user), __('business roles saved successfully'));
        }
        catch(\Throwable $th)
        {
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
                'unread_notifications_count' => $user->unreadNotifications()->count(),
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

    public function get_business_farms($id)
    {
         /** @var Business $business */
         $business = $this->businessRepository->find($id);

         if (empty($business))
             return $this->sendError('Business not found');
 
         return $this->sendResponse(FarmResource::collection($business->farms), 'Business farms retrieved successfully');
    }


    public function user_today_tasks(Request $request)
    {
        try{
            $weather_resp = WeatherApi::instance()->weather_api($request);
            $weather_data = $weather_resp['data'];

            $businesses = auth()->user()->allBusinesses()->with('tasks', function($query){
                $query->where('date', date('Y-m-d'));
            })->whereHas('tasks', function($q){
                $q->where('date', date('Y-m-d'));
            })->get();

            return $this->sendResponse([
                'weather_data' => $weather_data,
                'tasks' => BusinessWithTasksResource::collection($businesses)
            ], 'Today\'s tasks retrieved successfully');
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
                return $this->sendSuccess("You have unfollowed $business->com_name successfully");
            }
            else
            {
                auth()->user()->follow($business);
                return $this->sendSuccess("You have followed $business->com_name successfully");
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
            'business_fields' => BusinessField::all(),
            'agents' => $similar_dealers,
            'distributors' => $similar_dealers,
            'statuses' => [
                ['id' => 0, 'name' => app()->getLocale()=='ar' ?  'لم يبدأ بعد' : 'have not started'],
                ['id' => 1, 'name' => app()->getLocale()=='ar' ?  'تحت الإنشاء' : 'Under Construction'],
                ['id' => 2, 'name' => app()->getLocale()=='ar' ?  'يمارس نشاطه' : 'Working']
            ]
        ], 'business relations retrieved successfully');
    }

    public function update($id, UpdateBusinessAPIRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();

            /** @var Business $business */
            $business = $this->businessRepository->find($id);

            if (empty($business)) {
                return $this->sendError('Business not found');
            }

            $business = $this->businessRepository->update($input, $id);

            auth()->user()->attachRole('business-admin', $business);

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
                $business->main_asset()->delete();
                $asset = $this->store_file($main_asset, 'business-main');
                $business->assets()->create($asset);
            }

            if($cover_asset = $request->file('cover_asset'))
            {
                $business->cover_asset()->delete();
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
        /** @var Business $business */
        $business = $this->businessRepository->find($id);

        if (empty($business)) {
            return $this->sendError('Business not found');
        }

        $business->delete();

        return $this->sendSuccess('Business deleted successfully');
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
