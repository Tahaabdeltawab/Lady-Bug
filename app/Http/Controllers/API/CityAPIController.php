<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCityAPIRequest;
use App\Http\Requests\API\UpdateCityAPIRequest;
use App\Models\City;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CityResource;
use Response;

/**
 * Class CityController
 * @package App\Http\Controllers\API
 */

class CityAPIController extends AppBaseController
{
    /** @var  CityRepository */
    private $cityRepository;

    public function __construct(CityRepository $cityRepo)
    {
        $this->cityRepository = $cityRepo;

        $this->middleware('permission:cities.store')->only(['store']);
        $this->middleware('permission:cities.update')->only(['update']);
        $this->middleware('permission:cities.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $cities = $this->cityRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => CityResource::collection($cities)], 'Cities retrieved successfully');
    }

    public function store(CreateCityAPIRequest $request)
    {
        $input = $request->validated();

        $city = $this->cityRepository->create($input);

        return $this->sendResponse(new CityResource($city), 'City saved successfully');
    }

    public function show($id)
    {
        /** @var City $city */
        $city = $this->cityRepository->find($id);

        if (empty($city)) {
            return $this->sendError('City not found');
        }

        return $this->sendResponse(new CityResource($city), 'City retrieved successfully');
    }

    public function update($id, CreateCityAPIRequest $request)
    {
        $input = $request->validated();

        /** @var City $city */
        $city = $this->cityRepository->find($id);

        if (empty($city)) {
            return $this->sendError('City not found');
        }

        $city = $this->cityRepository->update($input, $id);

        return $this->sendResponse(new CityResource($city), 'City updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var City $city */
        $city = $this->cityRepository->find($id);

        if (empty($city)) {
            return $this->sendError('City not found');
        }

        $city->delete();

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
