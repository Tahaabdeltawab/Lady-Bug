<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePathogenGrowthStageAPIRequest;
use App\Http\Requests\API\UpdatePathogenGrowthStageAPIRequest;
use App\Models\PathogenGrowthStage;
use App\Repositories\PathogenGrowthStageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PathogenGrowthStageResource;
use Response;

/**
 * Class PathogenGrowthStageController
 * @package App\Http\Controllers\API
 */

class PathogenGrowthStageAPIController extends AppBaseController
{
    /** @var  PathogenGrowthStageRepository */
    private $pathogenGrowthStageRepository;

    public function __construct(PathogenGrowthStageRepository $pathogenGrowthStageRepo)
    {
        $this->pathogenGrowthStageRepository = $pathogenGrowthStageRepo;
    }

    /**
     * Display a listing of the PathogenGrowthStage.
     * GET|HEAD /pathogenGrowthStages
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $pathogenGrowthStages = $this->pathogenGrowthStageRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PathogenGrowthStageResource::collection($pathogenGrowthStages), 'Pathogen Growth Stages retrieved successfully');
    }

    /**
     * Store a newly created PathogenGrowthStage in storage.
     * POST /pathogenGrowthStages
     *
     * @param CreatePathogenGrowthStageAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePathogenGrowthStageAPIRequest $request)
    {
        $input = $request->validated();

        $pathogenGrowthStage = $this->pathogenGrowthStageRepository->create($input);

        return $this->sendResponse(new PathogenGrowthStageResource($pathogenGrowthStage), 'Pathogen Growth Stage saved successfully');
    }

    /**
     * Display the specified PathogenGrowthStage.
     * GET|HEAD /pathogenGrowthStages/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PathogenGrowthStage $pathogenGrowthStage */
        $pathogenGrowthStage = $this->pathogenGrowthStageRepository->find($id);

        if (empty($pathogenGrowthStage)) {
            return $this->sendError('Pathogen Growth Stage not found');
        }

        return $this->sendResponse(new PathogenGrowthStageResource($pathogenGrowthStage), 'Pathogen Growth Stage retrieved successfully');
    }

    /**
     * Update the specified PathogenGrowthStage in storage.
     * PUT/PATCH /pathogenGrowthStages/{id}
     *
     * @param int $id
     * @param UpdatePathogenGrowthStageAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePathogenGrowthStageAPIRequest $request)
    {
        $input = $request->validated();

        /** @var PathogenGrowthStage $pathogenGrowthStage */
        $pathogenGrowthStage = $this->pathogenGrowthStageRepository->find($id);

        if (empty($pathogenGrowthStage)) {
            return $this->sendError('Pathogen Growth Stage not found');
        }

        $pathogenGrowthStage = $this->pathogenGrowthStageRepository->update($input, $id);

        return $this->sendResponse(new PathogenGrowthStageResource($pathogenGrowthStage), 'PathogenGrowthStage updated successfully');
    }

    /**
     * Remove the specified PathogenGrowthStage from storage.
     * DELETE /pathogenGrowthStages/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PathogenGrowthStage $pathogenGrowthStage */
        $pathogenGrowthStage = $this->pathogenGrowthStageRepository->find($id);

        if (empty($pathogenGrowthStage)) {
            return $this->sendError('Pathogen Growth Stage not found');
        }

        $pathogenGrowthStage->delete();

        return $this->sendSuccess('Pathogen Growth Stage deleted successfully');
    }
}
