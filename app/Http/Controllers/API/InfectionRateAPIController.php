<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateInfectionRateAPIRequest;
use App\Http\Requests\API\UpdateInfectionRateAPIRequest;
use App\Models\InfectionRate;
use App\Repositories\InfectionRateRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\InfectionRateResource;
use Response;

/**
 * Class InfectionRateController
 * @package App\Http\Controllers\API
 */

class InfectionRateAPIController extends AppBaseController
{
    /** @var  InfectionRateRepository */
    private $infectionRateRepository;

    public function __construct(InfectionRateRepository $infectionRateRepo)
    {
        $this->infectionRateRepository = $infectionRateRepo;
    }

    /**
     * Display a listing of the InfectionRate.
     * GET|HEAD /infectionRates
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $infectionRates = $this->infectionRateRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(InfectionRateResource::collection($infectionRates), 'Infection Rates retrieved successfully');
    }

    /**
     * Store a newly created InfectionRate in storage.
     * POST /infectionRates
     *
     * @param CreateInfectionRateAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateInfectionRateAPIRequest $request)
    {
        $input = $request->all();

        $infectionRate = $this->infectionRateRepository->create($input);

        return $this->sendResponse(new InfectionRateResource($infectionRate), 'Infection Rate saved successfully');
    }

    /**
     * Display the specified InfectionRate.
     * GET|HEAD /infectionRates/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var InfectionRate $infectionRate */
        $infectionRate = $this->infectionRateRepository->find($id);

        if (empty($infectionRate)) {
            return $this->sendError('Infection Rate not found');
        }

        return $this->sendResponse(new InfectionRateResource($infectionRate), 'Infection Rate retrieved successfully');
    }

    /**
     * Update the specified InfectionRate in storage.
     * PUT/PATCH /infectionRates/{id}
     *
     * @param int $id
     * @param UpdateInfectionRateAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInfectionRateAPIRequest $request)
    {
        $input = $request->all();

        /** @var InfectionRate $infectionRate */
        $infectionRate = $this->infectionRateRepository->find($id);

        if (empty($infectionRate)) {
            return $this->sendError('Infection Rate not found');
        }

        $infectionRate = $this->infectionRateRepository->update($input, $id);

        return $this->sendResponse(new InfectionRateResource($infectionRate), 'InfectionRate updated successfully');
    }

    /**
     * Remove the specified InfectionRate from storage.
     * DELETE /infectionRates/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var InfectionRate $infectionRate */
        $infectionRate = $this->infectionRateRepository->find($id);

        if (empty($infectionRate)) {
            return $this->sendError('Infection Rate not found');
        }

        $infectionRate->delete();

        return $this->sendSuccess('Infection Rate deleted successfully');
    }
}
