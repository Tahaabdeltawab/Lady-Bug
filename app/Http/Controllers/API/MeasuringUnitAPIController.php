<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMeasuringUnitAPIRequest;
use App\Http\Requests\API\UpdateMeasuringUnitAPIRequest;
use App\Models\MeasuringUnit;
use App\Repositories\MeasuringUnitRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MeasuringUnitResource;
use Response;

/**
 * Class MeasuringUnitController
 * @package App\Http\Controllers\API
 */

class MeasuringUnitAPIController extends AppBaseController
{
    /** @var  MeasuringUnitRepository */
    private $measuringUnitRepository;

    public function __construct(MeasuringUnitRepository $measuringUnitRepo)
    {
        $this->measuringUnitRepository = $measuringUnitRepo;

        $this->middleware('permission:measuring_units.store')->only(['store']);
        $this->middleware('permission:measuring_units.update')->only(['update']);
        $this->middleware('permission:measuring_units.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $measuringUnits = $this->measuringUnitRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => MeasuringUnitResource::collection($measuringUnits)], 'Measuring Units retrieved successfully');
    }

    public function store(CreateMeasuringUnitAPIRequest $request)
    {
        $input = $request->validated();

        $measuringUnit = $this->measuringUnitRepository->save_localized($input);

        return $this->sendResponse(new MeasuringUnitResource($measuringUnit), 'Measuring Unit saved successfully');
    }

    public function show($id)
    {
        /** @var MeasuringUnit $measuringUnit */
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            return $this->sendError('Measuring Unit not found');
        }

        return $this->sendResponse(new MeasuringUnitResource($measuringUnit), 'Measuring Unit retrieved successfully');
    }

    public function update($id, CreateMeasuringUnitAPIRequest $request)
    {
        $input = $request->validated();

        /** @var MeasuringUnit $measuringUnit */
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            return $this->sendError('Measuring Unit not found');
        }

        $measuringUnit = $this->measuringUnitRepository->save_localized($input, $id);

        return $this->sendResponse(new MeasuringUnitResource($measuringUnit), 'MeasuringUnit updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var MeasuringUnit $measuringUnit */
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            return $this->sendError('Measuring Unit not found');
        }

        $measuringUnit->delete();

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
