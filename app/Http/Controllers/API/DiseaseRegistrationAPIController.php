<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDiseaseRegistrationAPIRequest;
use App\Http\Requests\API\UpdateDiseaseRegistrationAPIRequest;
use App\Models\DiseaseRegistration;
use App\Repositories\DiseaseRegistrationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DiseaseRegistrationAdminResource;
use App\Http\Resources\DiseaseRegistrationLgResource;
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

        $this->middleware('permission:disease_registrations.index')->only(['admin_index']);
        $this->middleware('permission:disease_registrations.show')->only(['admin_show']);
        $this->middleware('permission:disease_registrations.update')->only(['admin_update', 'toggle_confirm']);
    }

    // admin
    /**
     * Display a listing of the DiseaseRegistration.
     * GET|HEAD /diseaseRegistrations
     *
     * @param Request $request
     * @return Response
     */
    public function admin_index(Request $request)
    {
        return $this->index($request);
    }
    public function index(Request $request)
    {
        $diseaseRegistrations = $this->diseaseRegistrationRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => DiseaseRegistrationLgResource::collection($diseaseRegistrations['all']), 'meta' => $diseaseRegistrations['meta']], 'Disease Registrations retrieved successfully');
    }

    public function admin_show($id)
    {
        /** @var DiseaseRegistration $diseaseRegistration */
        $diseaseRegistration = $this->diseaseRegistrationRepository->find($id);

        if (empty($diseaseRegistration)) {
            return $this->sendError('Disease Registration not found');
        }

        return $this->sendResponse(new DiseaseRegistrationAdminResource($diseaseRegistration), 'Disease Registration retrieved successfully');
    }

    // user
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
        $input = $request->validated();
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

    public function getNearInfections(Request $request)
    {
        if($request->lat && $request->lon){
            // TODO if sent farm id, exclude it from dis regs :where dis_reg.farm_id != current farm id
            $near_infections = \Helper::getNearInfections($request->lat, $request->lon);
            return $this->sendResponse(['infections' => $near_infections], 'near infections retrieved successfully');
        }
        else
            return $this->sendError('Invalid lat or lon');
    }

    public function getRelations()
    {
        $data['diseases'] = DiseaseXsResource::collection(Disease::get(['id', 'name']));
        $data['infection_rates'] = InfectionRate::all();
        return $this->sendResponse($data, 'disease registration relations retrieved successfully');

    }

    // admin
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

    // admin
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

        return $this->sendResponse(new DiseaseRegistrationLgResource($diseaseRegistration), 'Disease Registration retrieved successfully');
    }

    // admin
    /**
     * Update the specified DiseaseRegistration in storage.
     * PUT/PATCH /diseaseRegistrations/{id}
     *
     * @param int $id
     * @param UpdateDiseaseRegistrationAPIRequest $request
     *
     * @return Response
     */
    public function admin_update($id, UpdateDiseaseRegistrationAPIRequest $request)
    {
        return $this->update($id, $request);
    }

    public function update($id, UpdateDiseaseRegistrationAPIRequest $request)
    {
        $input = $request->validated();

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
        return $this->sendResponse(new DiseaseRegistrationLgResource($diseaseRegistration), 'DiseaseRegistration updated successfully');
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
        try
        {
        /** @var DiseaseRegistration $diseaseRegistration */
        $diseaseRegistration = $this->diseaseRegistrationRepository->find($id);

        if (empty($diseaseRegistration)) {
            return $this->sendError('Disease Registration not found');
        }

        $diseaseRegistration->delete();

        return $this->sendSuccess('Disease Registration deleted successfully');
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
