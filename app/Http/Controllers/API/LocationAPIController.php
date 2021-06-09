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

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/locations",
     *      summary="Get a listing of the Locations.",
     *      tags={"Location"},
     *      description="Get all Locations",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Location")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $locations = $this->locationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(LocationResource::collection($locations), 'Locations retrieved successfully');
    }

    /**
     * @param CreateLocationAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/locations",
     *      summary="Store a newly created Location in storage",
     *      tags={"Location"},
     *      description="Store Location",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Location that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Location")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Location"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateLocationAPIRequest $request)
    {
        $input = $request->all();

        $location = $this->locationRepository->save_localized($input);

        return $this->sendResponse(new LocationResource($location), 'Location saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/locations/{id}",
     *      summary="Display the specified Location",
     *      tags={"Location"},
     *      description="Get Location",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Location",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Location"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Location $location */
        $location = $this->locationRepository->find($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        return $this->sendResponse(new LocationResource($location), 'Location retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateLocationAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/locations/{id}",
     *      summary="Update the specified Location in storage",
     *      tags={"Location"},
     *      description="Update Location",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Location",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Location that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Location")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Location"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateLocationAPIRequest $request)
    {
        $input = $request->all();

        /** @var Location $location */
        $location = $this->locationRepository->find($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        $location = $this->locationRepository->save_localized($input, $id);

        return $this->sendResponse(new LocationResource($location), 'Location updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/locations/{id}",
     *      summary="Remove the specified Location from storage",
     *      tags={"Location"},
     *      description="Delete Location",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Location",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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
