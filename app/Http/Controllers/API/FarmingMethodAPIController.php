<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmingMethodAPIRequest;
use App\Http\Requests\API\UpdateFarmingMethodAPIRequest;
use App\Models\FarmingMethod;
use App\Repositories\FarmingMethodRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmingMethodResource;
use Response;

/**
 * Class FarmingMethodController
 * @package App\Http\Controllers\API
 */

class FarmingMethodAPIController extends AppBaseController
{
    /** @var  FarmingMethodRepository */
    private $farmingMethodRepository;

    public function __construct(FarmingMethodRepository $farmingMethodRepo)
    {
        $this->farmingMethodRepository = $farmingMethodRepo;

        $this->middleware('permission:farming_methods.store')->only(['store']);
        $this->middleware('permission:farming_methods.update')->only(['update']);
        $this->middleware('permission:farming_methods.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $farmingMethods = $this->farmingMethodRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmingMethodResource::collection($farmingMethods)], 'Farming Methods retrieved successfully');
    }

    public function store(CreateFarmingMethodAPIRequest $request)
    {
        $input = $request->validated();

        $farmingMethod = $this->farmingMethodRepository->save_localized($input);

        return $this->sendResponse(new FarmingMethodResource($farmingMethod), 'Farming Method saved successfully');
    }

    public function show($id)
    {
        /** @var FarmingMethod $farmingMethod */
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            return $this->sendError('Farming Method not found');
        }

        return $this->sendResponse(new FarmingMethodResource($farmingMethod), 'Farming Method retrieved successfully');
    }

    public function update($id, CreateFarmingMethodAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmingMethod $farmingMethod */
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            return $this->sendError('Farming Method not found');
        }

        $farmingMethod = $this->farmingMethodRepository->save_localized($input, $id);

        return $this->sendResponse(new FarmingMethodResource($farmingMethod), 'FarmingMethod updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var FarmingMethod $farmingMethod */
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            return $this->sendError('Farming Method not found');
        }

        $farmingMethod->delete();

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
