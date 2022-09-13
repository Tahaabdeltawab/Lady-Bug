<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateInfectionSpreadStageAPIRequest;
use App\Http\Requests\API\UpdateInfectionSpreadStageAPIRequest;
use App\Models\InfectionSpreadStage;
use App\Repositories\InfectionSpreadStageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\InfectionSpreadStageResource;
use Response;

/**
 * Class InfectionSpreadStageController
 * @package App\Http\Controllers\API
 */

class InfectionSpreadStageAPIController extends AppBaseController
{
    /** @var  InfectionSpreadStageRepository */
    private $infectionSpreadStageRepository;

    public function __construct(InfectionSpreadStageRepository $infectionSpreadStageRepo)
    {
        $this->infectionSpreadStageRepository = $infectionSpreadStageRepo;
    }

    /**
     * Display a listing of the InfectionSpreadStage.
     * GET|HEAD /infectionSpreadStages
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $infectionSpreadStages = $this->infectionSpreadStageRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(InfectionSpreadStageResource::collection($infectionSpreadStages), 'Infection Spread Stages retrieved successfully');
    }

    /**
     * Store a newly created InfectionSpreadStage in storage.
     * POST /infectionSpreadStages
     *
     * @param CreateInfectionSpreadStageAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateInfectionSpreadStageAPIRequest $request)
    {
        $input = $request->all();

        $infectionSpreadStage = $this->infectionSpreadStageRepository->create($input);

        return $this->sendResponse(new InfectionSpreadStageResource($infectionSpreadStage), 'Infection Spread Stage saved successfully');
    }

    /**
     * Display the specified InfectionSpreadStage.
     * GET|HEAD /infectionSpreadStages/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var InfectionSpreadStage $infectionSpreadStage */
        $infectionSpreadStage = $this->infectionSpreadStageRepository->find($id);

        if (empty($infectionSpreadStage)) {
            return $this->sendError('Infection Spread Stage not found');
        }

        return $this->sendResponse(new InfectionSpreadStageResource($infectionSpreadStage), 'Infection Spread Stage retrieved successfully');
    }

    /**
     * Update the specified InfectionSpreadStage in storage.
     * PUT/PATCH /infectionSpreadStages/{id}
     *
     * @param int $id
     * @param UpdateInfectionSpreadStageAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInfectionSpreadStageAPIRequest $request)
    {
        $input = $request->all();

        /** @var InfectionSpreadStage $infectionSpreadStage */
        $infectionSpreadStage = $this->infectionSpreadStageRepository->find($id);

        if (empty($infectionSpreadStage)) {
            return $this->sendError('Infection Spread Stage not found');
        }

        $infectionSpreadStage = $this->infectionSpreadStageRepository->update($input, $id);

        return $this->sendResponse(new InfectionSpreadStageResource($infectionSpreadStage), 'InfectionSpreadStage updated successfully');
    }

    /**
     * Remove the specified InfectionSpreadStage from storage.
     * DELETE /infectionSpreadStages/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var InfectionSpreadStage $infectionSpreadStage */
        $infectionSpreadStage = $this->infectionSpreadStageRepository->find($id);

        if (empty($infectionSpreadStage)) {
            return $this->sendError('Infection Spread Stage not found');
        }

        $infectionSpreadStage->delete();

        return $this->sendSuccess('Infection Spread Stage deleted successfully');
    }
}
