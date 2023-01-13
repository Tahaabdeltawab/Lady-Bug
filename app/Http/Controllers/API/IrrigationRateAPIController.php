<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateIrrigationRateAPIRequest;
use App\Http\Requests\API\UpdateIrrigationRateAPIRequest;
use App\Models\IrrigationRate;
use App\Repositories\IrrigationRateRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\IrrigationRateResource;
use Response;

/**
 * Class IrrigationRateController
 * @package App\Http\Controllers\API
 */

class IrrigationRateAPIController extends AppBaseController
{
    /** @var  IrrigationRateRepository */
    private $irrigationRateRepository;

    public function __construct(IrrigationRateRepository $irrigationRateRepo)
    {
        $this->irrigationRateRepository = $irrigationRateRepo;
    }

    /**
     * Display a listing of the IrrigationRate.
     * GET|HEAD /irrigationRates
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $irrigationRates = $this->irrigationRateRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => IrrigationRateResource::collection($irrigationRates['all']), 'meta' => $irrigationRates['meta']], 'Irrigation Rates retrieved successfully');
    }

    /**
     * Store a newly created IrrigationRate in storage.
     * POST /irrigationRates
     *
     * @param CreateIrrigationRateAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateIrrigationRateAPIRequest $request)
    {
        $input = $request->validated();

        $irrigationRate = $this->irrigationRateRepository->create($input);

        return $this->sendResponse(new IrrigationRateResource($irrigationRate), 'Irrigation Rate saved successfully');
    }

    /**
     * Display the specified IrrigationRate.
     * GET|HEAD /irrigationRates/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var IrrigationRate $irrigationRate */
        $irrigationRate = $this->irrigationRateRepository->find($id);

        if (empty($irrigationRate)) {
            return $this->sendError('Irrigation Rate not found');
        }

        return $this->sendResponse(new IrrigationRateResource($irrigationRate), 'Irrigation Rate retrieved successfully');
    }

    /**
     * Update the specified IrrigationRate in storage.
     * PUT/PATCH /irrigationRates/{id}
     *
     * @param int $id
     * @param UpdateIrrigationRateAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateIrrigationRateAPIRequest $request)
    {
        $input = $request->validated();

        /** @var IrrigationRate $irrigationRate */
        $irrigationRate = $this->irrigationRateRepository->find($id);

        if (empty($irrigationRate)) {
            return $this->sendError('Irrigation Rate not found');
        }

        $irrigationRate = $this->irrigationRateRepository->update($input, $id);

        return $this->sendResponse(new IrrigationRateResource($irrigationRate), 'IrrigationRate updated successfully');
    }

    /**
     * Remove the specified IrrigationRate from storage.
     * DELETE /irrigationRates/{id}
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
        /** @var IrrigationRate $irrigationRate */
        $irrigationRate = $this->irrigationRateRepository->find($id);

        if (empty($irrigationRate)) {
            return $this->sendError('Irrigation Rate not found');
        }

        $irrigationRate->delete();

        return $this->sendSuccess('Irrigation Rate deleted successfully');
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
