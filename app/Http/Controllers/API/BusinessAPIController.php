<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessAPIRequest;
use App\Http\Requests\API\UpdateBusinessAPIRequest;
use App\Models\Business;
use App\Repositories\BusinessRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BusinessResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            $postResource = new PostResource($posts);
            return $this->sendResponse(['all' => $postResource->collection($posts)], 'business posts retrieved successfully');
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
        $input = $request->all();

        $business = $this->businessRepository->create($input);

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

        auth()->user()->attachRole('business-admin', $business);

        return $this->sendResponse(new BusinessResource($business), 'Business saved successfully');
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

            $user = $this->userRepository->find($request->user);
            $business = Business::find($request->business);

            $user->attachRole($request->role, $business);

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
            ]);

            if ($validator->fails()) {
                return $this->sendError(json_encode($validator->errors()));
            }

            $user = User::find($request->user);
            $business = Business::find($request->business);


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
                }
                else            // first attach role
                {
                    //send invitation to assignee user
                    if($user->is_notifiable){

                    $user->notify(new \App\Notifications\BusinessInvitation(
                        auth()->user(),
                        $role,
                        $business,
                        URL::temporarySignedRoute('api.businesses.roles.first_attach', now()->addDays(10),['user' => $request->user,'business' => $request->business,'role' => $request->role,]),
                        URL::temporarySignedRoute('api.businesses.roles.decline_business_invitation', now()->addDays(10),['user' => $request->user,'business' => $request->business,'role' => $request->role,])
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

    /**
     * Update the specified Business in storage.
     * PUT/PATCH /businesses/{id}
     *
     * @param int $id
     * @param UpdateBusinessAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBusinessAPIRequest $request)
    {
        $input = $request->all();

        /** @var Business $business */
        $business = $this->businessRepository->find($id);

        if (empty($business)) {
            return $this->sendError('Business not found');
        }

        $business = $this->businessRepository->update($input, $id);

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

        return $this->sendResponse(new BusinessResource($business), 'Business updated successfully');
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
