<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateIrrigationWayAPIRequest;
use App\Http\Requests\API\UpdateIrrigationWayAPIRequest;
use App\Models\IrrigationWay;
use App\Repositories\IrrigationWayRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\IrrigationWayResource;
use Response;

/**
 * Class IrrigationWayController
 * @package App\Http\Controllers\API
 */

class IrrigationWayAPIController extends AppBaseController
{
    /** @var  IrrigationWayRepository */
    private $irrigationWayRepository;

    public function __construct(IrrigationWayRepository $irrigationWayRepo)
    {
        $this->irrigationWayRepository = $irrigationWayRepo;

        $this->middleware('permission:irrigation_ways.store')->only(['store']);
        $this->middleware('permission:irrigation_ways.update')->only(['update']);
        $this->middleware('permission:irrigation_ways.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $irrigationWays = $this->irrigationWayRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => IrrigationWayResource::collection($irrigationWays)], 'Irrigation Ways retrieved successfully');
    }

    public function store(CreateIrrigationWayAPIRequest $request)
    {
        $input = $request->validated();

        $irrigationWay = $this->irrigationWayRepository->create($input);

        return $this->sendResponse(new IrrigationWayResource($irrigationWay), 'Irrigation Way saved successfully');
    }

    public function show($id)
    {
        /** @var IrrigationWay $irrigationWay */
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            return $this->sendError('Irrigation Way not found');
        }

        return $this->sendResponse(new IrrigationWayResource($irrigationWay), 'Irrigation Way retrieved successfully');
    }

    public function update($id, CreateIrrigationWayAPIRequest $request)
    {
        $input = $request->validated();

        /** @var IrrigationWay $irrigationWay */
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            return $this->sendError('Irrigation Way not found');
        }

        $irrigationWay = $this->irrigationWayRepository->update($input, $id);

        return $this->sendResponse(new IrrigationWayResource($irrigationWay), 'IrrigationWay updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var IrrigationWay $irrigationWay */
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            return $this->sendError('Irrigation Way not found');
        }

        $irrigationWay->delete();

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
