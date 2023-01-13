<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmingWayAPIRequest;
use App\Http\Requests\API\UpdateFarmingWayAPIRequest;
use App\Models\FarmingWay;
use App\Repositories\FarmingWayRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmingWayResource;
use Response;

/**
 * Class FarmingWayController
 * @package App\Http\Controllers\API
 */

class FarmingWayAPIController extends AppBaseController
{
    /** @var  FarmingWayRepository */
    private $farmingWayRepository;

    public function __construct(FarmingWayRepository $farmingWayRepo)
    {
        $this->farmingWayRepository = $farmingWayRepo;

        $this->middleware('permission:farming_ways.store')->only(['store']);
        $this->middleware('permission:farming_ways.update')->only(['update']);
        $this->middleware('permission:farming_ways.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        // ! don't change repository all method as its first argument ($search = $request->exce...)
        // ! is used to filter farming ways and breeding ways
        // ! /farming_ways?type=breeding
        $farmingWays = $this->farmingWayRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => FarmingWayResource::collection($farmingWays['all']), 'meta' => $farmingWays['meta']], 'Farming Ways retrieved successfully');
    }

    public function store(CreateFarmingWayAPIRequest $request)
    {
        $input = $request->validated();

        $farmingWay = $this->farmingWayRepository->create($input);

        return $this->sendResponse(new FarmingWayResource($farmingWay), 'Farming Way saved successfully');
    }

    public function show($id)
    {
        /** @var FarmingWay $farmingWay */
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            return $this->sendError('Farming Way not found');
        }

        return $this->sendResponse(new FarmingWayResource($farmingWay), 'Farming Way retrieved successfully');
    }

    public function update($id, CreateFarmingWayAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmingWay $farmingWay */
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            return $this->sendError('Farming Way not found');
        }

        $farmingWay = $this->farmingWayRepository->update($input, $id);

        return $this->sendResponse(new FarmingWayResource($farmingWay), 'FarmingWay updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var FarmingWay $farmingWay */
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            return $this->sendError('Farming Way not found');
        }

        $farmingWay->delete();

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
