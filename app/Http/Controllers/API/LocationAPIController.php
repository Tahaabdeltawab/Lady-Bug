<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLocationAPIRequest;
use App\Http\Requests\API\UpdateLocationAPIRequest;
use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\LocationResource;
use Response;

/**
 * Class LocationController
 * @package App\Http\Controllers\API
 */

class LocationAPIController extends AppBaseController
{
    /** @var  LocationRepository */
    private $locationRepository;

    public function __construct(LocationRepository $locationRepo)
    {
        $this->locationRepository = $locationRepo;
    }

    public function index(Request $request)
    {
        $locations = $this->locationRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => LocationResource::collection($locations['all']), 'meta' => $locations['meta']], 'Locations retrieved successfully');
    }

    public function store(CreateLocationAPIRequest $request)
    {
        $input = $request->validated();

        $location = $this->locationRepository->create($input);

        return $this->sendResponse(new LocationResource($location), 'Location saved successfully');
    }

    public function show($id)
    {
        /** @var Location $location */
        $location = $this->locationRepository->find($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        return $this->sendResponse(new LocationResource($location), 'Location retrieved successfully');
    }

    public function update($id, CreateLocationAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Location $location */
        $location = $this->locationRepository->find($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        $location = $this->locationRepository->update($input, $id);

        return $this->sendResponse(new LocationResource($location), 'Location updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var Location $location */
        $location = $this->locationRepository->find($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        $location->delete();

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
