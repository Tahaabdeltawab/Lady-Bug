<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeStageAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeStageAPIRequest;
use App\Models\FarmedTypeStage;
use App\Repositories\FarmedTypeStageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeStageResource;
use Response;

/**
 * Class FarmedTypeStageController
 * @package App\Http\Controllers\API
 */

class FarmedTypeStageAPIController extends AppBaseController
{
    /** @var  FarmedTypeStageRepository */
    private $farmedTypeStageRepository;

    public function __construct(FarmedTypeStageRepository $farmedTypeStageRepo)
    {
        $this->farmedTypeStageRepository = $farmedTypeStageRepo;

        $this->middleware('permission:farmed_type_stages.store')->only(['store']);
        $this->middleware('permission:farmed_type_stages.update')->only(['update']);
        $this->middleware('permission:farmed_type_stages.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $farmedTypeStages = $this->farmedTypeStageRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeStageResource::collection($farmedTypeStages)], 'Farmed Type Stages retrieved successfully');
    }

    public function store(CreateFarmedTypeStageAPIRequest $request)
    {
        $input = $request->validated();

        $farmedTypeStage = $this->farmedTypeStageRepository->save_localized($input);

        return $this->sendResponse(new FarmedTypeStageResource($farmedTypeStage), 'Farmed Type Stage saved successfully');
    }

    public function show($id)
    {
        /** @var FarmedTypeStage $farmedTypeStage */
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            return $this->sendError('Farmed Type Stage not found');
        }

        return $this->sendResponse(new FarmedTypeStageResource($farmedTypeStage), 'Farmed Type Stage retrieved successfully');
    }

    public function update($id, CreateFarmedTypeStageAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeStage $farmedTypeStage */
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            return $this->sendError('Farmed Type Stage not found');
        }

        $farmedTypeStage = $this->farmedTypeStageRepository->save_localized($input, $id);

        return $this->sendResponse(new FarmedTypeStageResource($farmedTypeStage), 'FarmedTypeStage updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var FarmedTypeStage $farmedTypeStage */
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            return $this->sendError('Farmed Type Stage not found');
        }

        $farmedTypeStage->delete();

          return $this->sendSuccess('Model deleted successfully');
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
