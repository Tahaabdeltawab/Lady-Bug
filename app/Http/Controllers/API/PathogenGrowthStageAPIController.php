<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePathogenGrowthStageAPIRequest;
use App\Http\Requests\API\UpdatePathogenGrowthStageAPIRequest;
use App\Models\PathogenGrowthStage;
use App\Repositories\PathogenGrowthStageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PathogenGrowthStageResource;
use Illuminate\Support\Facades\DB;
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
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => PathogenGrowthStageResource::collection($pathogenGrowthStages['all']), 'meta' => $pathogenGrowthStages['meta']], 'Pathogen Growth Stages retrieved successfully');
    }

    public function by_pa_id($pathogen_id)
    {
        $stages = PathogenGrowthStage::where('pathogen_id', $pathogen_id)->get();
        return $this->sendResponse(PathogenGrowthStageResource::collection($stages), 'stages retrieved successfully');
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

        if($assets = $request->file('assets'))
            {
                foreach($assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'pathogen-growth-stage');
                    $assets[] = $pathogenGrowthStage->assets()->create($oneasset);
                }
            }

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

        if($assets = $request->file('assets'))
        {
            foreach ($pathogenGrowthStage->assets as $ass) {
                $ass->delete();
            }
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'pathogen-growth-stage');
                $assets[] = $pathogenGrowthStage->assets()->create($oneasset);
            }
        }

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
        try{
            /** @var PathogenGrowthStage $pathogenGrowthStage */
            $pathogenGrowthStage = $this->pathogenGrowthStageRepository->find($id);

            if (empty($pathogenGrowthStage)) {
                return $this->sendError('Pathogen Growth Stage not found');
            }
            DB::beginTransaction();

            foreach(AcPaGrowthStage::where('pathogen_growth_stage_id', $pathogenGrowthStage->id)->get() as $acpa){
                foreach($acpa->assets as $ass){
                    $ass->delete();
                }
                $acpa->delete();
            }
            foreach($pathogenGrowthStage->assets as $ass){
                $ass->delete();
            }
            $pathogenGrowthStage->delete();
            DB::commit();
            return $this->sendSuccess('Pathogen Growth Stage deleted successfully');
        }catch(\Throwable $th)
        {
            DB::rollBack();
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }
}
