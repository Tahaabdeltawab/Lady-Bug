<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateConsultancyProfileAPIRequest;
use App\Http\Requests\API\UpdateConsultancyProfileAPIRequest;
use App\Models\ConsultancyProfile;
use App\Repositories\ConsultancyProfileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ConsultancyProfileResource;
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
        $input = $request->all();

        $consultancyProfile = $this->consultancyProfileRepository->create($input);

        return $this->sendResponse(new ConsultancyProfileResource($consultancyProfile), 'Consultancy Profile saved successfully');
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

        $consultancyProfile->delete();

        return $this->sendSuccess('Consultancy Profile deleted successfully');
    }
}
