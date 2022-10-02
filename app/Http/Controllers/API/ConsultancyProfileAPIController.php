<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateConsultancyProfileAPIRequest;
use App\Http\Requests\API\UpdateConsultancyProfileAPIRequest;
use App\Models\ConsultancyProfile;
use App\Repositories\ConsultancyProfileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ConsultancyProfileResource;
use App\Http\Resources\UserConsResource;
use App\Models\User;
use App\Models\WorkField;
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

    public function __construct(ConsultancyProfileRepository $consultancyProfileRepo)
    {
        $this->consultancyProfileRepository = $consultancyProfileRepo;
    }

    /**
     * Display a listing of the ConsultancyProfile.
     * GET|HEAD /consultancyProfiles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $consultancyProfiles = $this->consultancyProfileRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ConsultancyProfileResource::collection($consultancyProfiles), 'Consultancy Profiles retrieved successfully');
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

            $input = $request->all();
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
        $input = $request->all();

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
        $input = $request->all();

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

    public function delete_mine()
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
}
