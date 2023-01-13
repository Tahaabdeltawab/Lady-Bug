<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSoilTypeAPIRequest;
use App\Http\Requests\API\UpdateSoilTypeAPIRequest;
use App\Models\SoilType;
use App\Repositories\SoilTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SoilTypeResource;
use Response;

/**
 * Class SoilTypeController
 * @package App\Http\Controllers\API
 */

class SoilTypeAPIController extends AppBaseController
{
    /** @var  SoilTypeRepository */
    private $soilTypeRepository;

    public function __construct(SoilTypeRepository $soilTypeRepo)
    {
        $this->soilTypeRepository = $soilTypeRepo;

        $this->middleware('permission:soil_types.store')->only(['store']);
        $this->middleware('permission:soil_types.update')->only(['update']);
        $this->middleware('permission:soil_types.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $soilTypes = $this->soilTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => SoilTypeResource::collection($soilTypes['all']), 'meta' => $soilTypes['meta']], 'Soil Types retrieved successfully');
    }

    public function store(CreateSoilTypeAPIRequest $request)
    {
        $input = $request->validated();

        $soilType = $this->soilTypeRepository->create($input);

        return $this->sendResponse(new SoilTypeResource($soilType), 'Soil Type saved successfully');
    }

    public function show($id)
    {
        /** @var SoilType $soilType */
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            return $this->sendError('Soil Type not found');
        }

        return $this->sendResponse(new SoilTypeResource($soilType), 'Soil Type retrieved successfully');
    }

    public function update($id, CreateSoilTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SoilType $soilType */
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            return $this->sendError('Soil Type not found');
        }

        $soilType = $this->soilTypeRepository->update($input, $id);

        return $this->sendResponse(new SoilTypeResource($soilType), 'SoilType updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var SoilType $soilType */
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            return $this->sendError('Soil Type not found');
        }

        $soilType->delete();

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
