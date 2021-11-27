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

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/measuringUnits",
     *      summary="Get a listing of the MeasuringUnits.",
     *      tags={"MeasuringUnit"},
     *      description="Get all MeasuringUnits",
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
     *                  @SWG\Items(ref="#/definitions/MeasuringUnit")
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
        $measuringUnits = $this->measuringUnitRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => MeasuringUnitResource::collection($measuringUnits)], 'Measuring Units retrieved successfully');
    }

    /**
     * @param CreateMeasuringUnitAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/measuringUnits",
     *      summary="Store a newly created MeasuringUnit in storage",
     *      tags={"MeasuringUnit"},
     *      description="Store MeasuringUnit",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="MeasuringUnit that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/MeasuringUnit")
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
     *                  ref="#/definitions/MeasuringUnit"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateMeasuringUnitAPIRequest $request)
    {
        $input = $request->validated();

        $measuringUnit = $this->measuringUnitRepository->save_localized($input);

        return $this->sendResponse(new MeasuringUnitResource($measuringUnit), 'Measuring Unit saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/measuringUnits/{id}",
     *      summary="Display the specified MeasuringUnit",
     *      tags={"MeasuringUnit"},
     *      description="Get MeasuringUnit",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of MeasuringUnit",
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
     *                  ref="#/definitions/MeasuringUnit"
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
        /** @var MeasuringUnit $measuringUnit */
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            return $this->sendError('Measuring Unit not found');
        }

        return $this->sendResponse(new MeasuringUnitResource($measuringUnit), 'Measuring Unit retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateMeasuringUnitAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/measuringUnits/{id}",
     *      summary="Update the specified MeasuringUnit in storage",
     *      tags={"MeasuringUnit"},
     *      description="Update MeasuringUnit",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of MeasuringUnit",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="MeasuringUnit that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/MeasuringUnit")
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
     *                  ref="#/definitions/MeasuringUnit"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/measuringUnits/{id}",
     *      summary="Remove the specified MeasuringUnit from storage",
     *      tags={"MeasuringUnit"},
     *      description="Delete MeasuringUnit",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of MeasuringUnit",
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
