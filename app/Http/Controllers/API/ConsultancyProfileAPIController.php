<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateConsultancyProfileAPIRequest;
use App\Http\Requests\API\UpdateConsultancyProfileAPIRequest;
use App\Models\ConsultancyProfile;
use App\Repositories\ConsultancyProfileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ConsultancyProfileResource;
use App\Http\Resources\UserConsAdminXsResource;
use App\Http\Resources\UserConsResource;
use App\Models\User;
use App\Models\WorkField;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Response;

/**
 * Class ConsultancyProfileController
 * @package App\Http\Controllers\API
 */

class ConsultancyProfileAPIController extends AppBaseController
{
    /** @var  ConsultancyProfileRepository */
    private $consultancyProfileRepository;
    private $userRepository;

    public function __construct(ConsultancyProfileRepository $consultancyProfileRepo, UserRepository $userRepo)
    {
        $this->consultancyProfileRepository = $consultancyProfileRepo;
        $this->userRepository = $userRepo;
    }



    //admin
    public function admin_index(Request $request)
    {
        $consultants = $this->userRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage'),
            ['cons'],
            ['consultancyProfile'],
            ['is_consultant', ...User::$selects]
        );

        return $this->sendResponse(['all' => UserConsAdminXsResource::collection($consultants['all']), 'meta' => $consultants['meta']], 'Consultants retrieved successfully');
    }

    public function toggle_activate($id)
    {
        $cons = $this->consultancyProfileRepository->findBy(['user_id' => $id]);
        if (empty($cons))
            return $this->sendError('Consultant not found');

        $msg = $cons->status ? 'Consultant has been in-active' : 'Consultant has been active';
        $cons->status = !$cons->status;
        $cons->save();

        return $this->sendSuccess($msg);
    }
    public function index(Request $request)
    {
        $consultancyProfiles = $this->consultancyProfileRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage'),
            ['active']
        );

        return $this->sendResponse(['all' => ConsultancyProfileResource::collection($consultancyProfiles['all']), 'meta' => $consultancyProfiles['meta']], 'Consultancy Profiles retrieved successfully');
    }

    public function getRelations()
    {
        $data['work_fields'] = WorkField::all();
        return $this->sendResponse($data, 'data retrieved');
    }
    /**
     * Store a newly created ConsultancyProfile in storage.
     * POST /consultancyProfiles
     *
     * @param CreateConsultancyProfileAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateConsultancyProfileAPIRequest $request)
    {
        try {
            DB::beginTransaction();

            if(auth()->user()->consultancyProfile()->exists())
                return $this->sendError('Already registerd before');

            $input = $request->validated();
            $input['user_id'] = auth()->id();
            $consultancyProfile = $this->consultancyProfileRepository->create($input);

            auth()->user()->is_consultant = true;
            auth()->user()->save();

            $consultancyProfile->workFields()->attach($request->work_fields);
            if($ocps = $request->offline_consultancy_plans){
                foreach ($ocps as $ocp) {
                    $consultancyProfile->offlineConsultancyPlans()->create($ocp);
                }
            }
            DB::commit();
            return $this->sendResponse(new ConsultancyProfileResource($consultancyProfile), 'Consultancy Profile saved successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Display the specified ConsultancyProfile.
     * GET|HEAD /consultancyProfiles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ConsultancyProfile $consultancyProfile */
        $consultancyProfile = $this->consultancyProfileRepository->find($id);

        if (empty($consultancyProfile)) {
            return $this->sendError('Consultancy Profile not found');
        }

        return $this->sendResponse(new ConsultancyProfileResource($consultancyProfile), 'Consultancy Profile retrieved successfully');
    }

    public function mine()
    {
        $consultancyProfile = auth()->user()->consultancyProfile;

        if (empty($consultancyProfile)) {
            return $this->sendError('Consultancy Profile not found');
        }

        return $this->sendResponse(new ConsultancyProfileResource($consultancyProfile), 'Consultancy Profile retrieved successfully');
    }

    public function user_consultancy_profile($user_id)
    {
        $user = User::select('id', 'name', 'human_job_id')->find($user_id);
        if(empty($user))
            return $this->sendError('User not found');
        return $this->sendResponse(new UserConsResource($user), 'Consultancy Profile retrieved successfully');
    }

    /**
     * Update the specified ConsultancyProfile in storage.
     * PUT/PATCH /consultancyProfiles/{id}
     *
     * @param int $id
     * @param UpdateConsultancyProfileAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConsultancyProfileAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ConsultancyProfile $consultancyProfile */
        $consultancyProfile = $this->consultancyProfileRepository->find($id);

        if (empty($consultancyProfile)) {
            return $this->sendError('Consultancy Profile not found');
        }

        $consultancyProfile = $this->consultancyProfileRepository->update($input, $id);
        $consultancyProfile->workFields()->sync($request->work_fields);

        if($ocps = $request->offline_consultancy_plans){
            $consultancyProfile->offlineConsultancyPlans()->delete();
            foreach ($ocps as $ocp) {
                $consultancyProfile->offlineConsultancyPlans()->create($ocp);
            }
        }

        return $this->sendResponse(new ConsultancyProfileResource($consultancyProfile), 'ConsultancyProfile updated successfully');
    }

    public function update_my_consultancy_profile(UpdateConsultancyProfileAPIRequest $request)
    {
        $input = $request->validated();

        $consultancyProfile = auth()->user()->consultancyProfile;

        if (empty($consultancyProfile)) {
            return $this->sendError('Consultancy Profile not found');
        }

        $consultancyProfile = $this->consultancyProfileRepository->update($input, $consultancyProfile->id);
        $consultancyProfile->workFields()->sync($request->work_fields);

        if($ocps = $request->offline_consultancy_plans){
            $consultancyProfile->offlineConsultancyPlans()->delete();
            foreach ($ocps as $ocp) {
                $consultancyProfile->offlineConsultancyPlans()->create($ocp);
            }
        }

        return $this->sendResponse(new ConsultancyProfileResource($consultancyProfile), 'ConsultancyProfile updated successfully');
    }

    /**
     * Remove the specified ConsultancyProfile from storage.
     * DELETE /consultancyProfiles/{id}
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
        /** @var ConsultancyProfile $consultancyProfile */
        $consultancyProfile = $this->consultancyProfileRepository->find($id);

        if (empty($consultancyProfile)) {
            return $this->sendError('Consultancy Profile not found');
        }

        $consultancyProfile->workFields()->detach();
        $consultancyProfile->offlineConsultancyPlans()->delete();
        $consultancyProfile->delete();
        auth()->user()->is_consultant = false;
        auth()->user()->save();
        return $this->sendSuccess('Consultancy Profile deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }

    public function delete_mine()
    {
        try
        {
        /** @var ConsultancyProfile $consultancyProfile */
        $consultancyProfile = auth()->user()->consultancyProfile;

        if (empty($consultancyProfile)) {
            return $this->sendError('Consultancy Profile not found');
        }

        $consultancyProfile->workFields()->detach();
        $consultancyProfile->offlineConsultancyPlans()->delete();
        $consultancyProfile->delete();
        auth()->user()->is_consultant = false;
        auth()->user()->save();
        return $this->sendSuccess('Consultancy Profile deleted successfully');
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
