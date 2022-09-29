<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDiseaseRegistrationAPIRequest;
use App\Http\Requests\API\UpdateDiseaseRegistrationAPIRequest;
use App\Models\DiseaseRegistration;
use App\Repositories\DiseaseRegistrationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DiseaseRegistrationResource;
use App\Http\Resources\DiseaseXsResource;
use App\Models\Disease;
use App\Models\InfectionRate;
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
        $input['user_id'] = auth()->id();
        $diseaseRegistration = $this->diseaseRegistrationRepository->create($input);
        if($assets = $request->file('assets'))
        {
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'disease-reg');
                $diseaseRegistration->assets()->create($oneasset);
            }
        }
        return $this->sendResponse(new DiseaseRegistrationResource($diseaseRegistration), 'Disease Registration saved successfully');
    }

    public function getRelations()
    {
        $data['diseases'] = DiseaseXsResource::collection(Disease::get(['id', 'name']));
        $data['infection_rates'] = InfectionRate::all();
        return $this->sendResponse($data, 'disease registration relations retrieved successfully');

    }

    public function toggle_confirm($id)
    {
        $diseaseRegistration = DiseaseRegistration::find($id);
        if (empty($diseaseRegistration))
            return $this->sendError('Disease Registration not found');

        $msg = $diseaseRegistration->status ? 'Disease Registration confirmation deleted' : 'Disease Registration has been confirmed';
        $diseaseRegistration->status = !$diseaseRegistration->status;
        $diseaseRegistration->save();

        return $this->sendSuccess($msg);
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
        if($assets = $request->file('assets'))
        {
            foreach ($diseaseRegistration->assets as $ass) {
                $ass->delete();
            }
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'disease-reg');
                $diseaseRegistration->assets()->create($oneasset);
            }
        }
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
