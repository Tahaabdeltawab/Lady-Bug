<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmActivityTypeAPIRequest;
use App\Http\Requests\API\UpdateFarmActivityTypeAPIRequest;
use App\Models\FarmActivityType;
use App\Repositories\FarmActivityTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmActivityTypeResource;
use App\Models\Farm;
use Response;

/**
 * Class FarmActivityTypeController
 * @package App\Http\Controllers\API
 */

class FarmActivityTypeAPIController extends AppBaseController
{
    /** @var  FarmActivityTypeRepository */
    private $farmActivityTypeRepository;

    public function __construct(FarmActivityTypeRepository $farmActivityTypeRepo)
    {
        $this->farmActivityTypeRepository = $farmActivityTypeRepo;
    }

    public function index(Request $request)
    {
        $farmActivityTypes = $this->farmActivityTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => FarmActivityTypeResource::collection($farmActivityTypes['all']), 'meta' => $farmActivityTypes['meta']], 'Farm Activity Types retrieved successfully');
    }

    public function store(CreateFarmActivityTypeAPIRequest $request)
    {
        $input = $request->validated();

        $farmActivityType = $this->farmActivityTypeRepository->create($input);

        return $this->sendResponse(new FarmActivityTypeResource($farmActivityType), 'Farm Activity Type saved successfully');
    }

    public function show($id)
    {
        /** @var FarmActivityType $farmActivityType */
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            return $this->sendError('Farm Activity Type not found');
        }

        return $this->sendResponse(new FarmActivityTypeResource($farmActivityType), 'Farm Activity Type retrieved successfully');
    }

    public function update($id, CreateFarmActivityTypeAPIRequest $request)
    {
        if(in_array($id, Farm::$used_farm_activity_types))
            return $this->sendError('Used Farm Activity Types are not editable');

        $input = $request->validated();

        /** @var FarmActivityType $farmActivityType */
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            return $this->sendError('Farm Activity Type not found');
        }

        $farmActivityType = $this->farmActivityTypeRepository->update($input, $id);

        return $this->sendResponse(new FarmActivityTypeResource($farmActivityType), 'FarmActivityType updated successfully');
    }

    public function destroy($id)
    {
        if(in_array($id, Farm::$used_farm_activity_types))
            return $this->sendError('Used Farm Activity Types are not deletable');

        try
        {
        /** @var FarmActivityType $farmActivityType */
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            return $this->sendError('Farm Activity Type not found');
        }

        $farmActivityType->delete();

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
