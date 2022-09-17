<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDiseaseRegistrationAPIRequest;
use App\Http\Requests\API\UpdateDiseaseRegistrationAPIRequest;
use App\Models\DiseaseRegistration;
use App\Repositories\DiseaseRegistrationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DiseaseRegistrationResource;
use Response;

/**
 * Class DiseaseRegistrationController
 * @package App\Http\Controllers\API
 */

class DiseaseRegistrationAPIController extends AppBaseController
{
    /** @var  DiseaseRegistrationRepository */
    private $diseaseRegistrationRepository;

    public function __construct(DiseaseRegistrationRepository $diseaseRegistrationRepo)
    {
        $this->diseaseRegistrationRepository = $diseaseRegistrationRepo;
    }

    /**
     * Display a listing of the DiseaseRegistration.
     * GET|HEAD /diseaseRegistrations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $diseaseRegistrations = $this->diseaseRegistrationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(DiseaseRegistrationResource::collection($diseaseRegistrations), 'Disease Registrations retrieved successfully');
    }

    /**
     * Store a newly created DiseaseRegistration in storage.
     * POST /diseaseRegistrations
     *
     * @param CreateDiseaseRegistrationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDiseaseRegistrationAPIRequest $request)
    {
        $input = $request->all();

        $diseaseRegistration = $this->diseaseRegistrationRepository->create($input);

        return $this->sendResponse(new DiseaseRegistrationResource($diseaseRegistration), 'Disease Registration saved successfully');
    }

    /**
     * Display the specified DiseaseRegistration.
     * GET|HEAD /diseaseRegistrations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DiseaseRegistration $diseaseRegistration */
        $diseaseRegistration = $this->diseaseRegistrationRepository->find($id);

        if (empty($diseaseRegistration)) {
            return $this->sendError('Disease Registration not found');
        }

        return $this->sendResponse(new DiseaseRegistrationResource($diseaseRegistration), 'Disease Registration retrieved successfully');
    }

    /**
     * Update the specified DiseaseRegistration in storage.
     * PUT/PATCH /diseaseRegistrations/{id}
     *
     * @param int $id
     * @param UpdateDiseaseRegistrationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDiseaseRegistrationAPIRequest $request)
    {
        $input = $request->all();

        /** @var DiseaseRegistration $diseaseRegistration */
        $diseaseRegistration = $this->diseaseRegistrationRepository->find($id);

        if (empty($diseaseRegistration)) {
            return $this->sendError('Disease Registration not found');
        }

        $diseaseRegistration = $this->diseaseRegistrationRepository->update($input, $id);

        return $this->sendResponse(new DiseaseRegistrationResource($diseaseRegistration), 'DiseaseRegistration updated successfully');
    }

    /**
     * Remove the specified DiseaseRegistration from storage.
     * DELETE /diseaseRegistrations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DiseaseRegistration $diseaseRegistration */
        $diseaseRegistration = $this->diseaseRegistrationRepository->find($id);

        if (empty($diseaseRegistration)) {
            return $this->sendError('Disease Registration not found');
        }

        $diseaseRegistration->delete();

        return $this->sendSuccess('Disease Registration deleted successfully');
    }
}
