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
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/irrigationWays",
     *      summary="Get a listing of the IrrigationWays.",
     *      tags={"IrrigationWay"},
     *      description="Get all IrrigationWays",
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
     *                  @SWG\Items(ref="#/definitions/IrrigationWay")
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
        $irrigationWays = $this->irrigationWayRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => IrrigationWayResource::collection($irrigationWays)], 'Irrigation Ways retrieved successfully');
    }

    /**
     * @param CreateIrrigationWayAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/irrigationWays",
     *      summary="Store a newly created IrrigationWay in storage",
     *      tags={"IrrigationWay"},
     *      description="Store IrrigationWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="IrrigationWay that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/IrrigationWay")
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
     *                  ref="#/definitions/IrrigationWay"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateIrrigationWayAPIRequest $request)
    {
        $input = $request->validated();

        $irrigationWay = $this->irrigationWayRepository->save_localized($input);

        return $this->sendResponse(new IrrigationWayResource($irrigationWay), 'Irrigation Way saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/irrigationWays/{id}",
     *      summary="Display the specified IrrigationWay",
     *      tags={"IrrigationWay"},
     *      description="Get IrrigationWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of IrrigationWay",
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
     *                  ref="#/definitions/IrrigationWay"
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
        /** @var IrrigationWay $irrigationWay */
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            return $this->sendError('Irrigation Way not found');
        }

        return $this->sendResponse(new IrrigationWayResource($irrigationWay), 'Irrigation Way retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateIrrigationWayAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/irrigationWays/{id}",
     *      summary="Update the specified IrrigationWay in storage",
     *      tags={"IrrigationWay"},
     *      description="Update IrrigationWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of IrrigationWay",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="IrrigationWay that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/IrrigationWay")
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
     *                  ref="#/definitions/IrrigationWay"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateIrrigationWayAPIRequest $request)
    {
        $input = $request->validated();

        /** @var IrrigationWay $irrigationWay */
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            return $this->sendError('Irrigation Way not found');
        }

        $irrigationWay = $this->irrigationWayRepository->save_localized($input, $id);

        return $this->sendResponse(new IrrigationWayResource($irrigationWay), 'IrrigationWay updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/irrigationWays/{id}",
     *      summary="Remove the specified IrrigationWay from storage",
     *      tags={"IrrigationWay"},
     *      description="Delete IrrigationWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of IrrigationWay",
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
        /** @var IrrigationWay $irrigationWay */
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            return $this->sendError('Irrigation Way not found');
        }

        $irrigationWay->delete();

        return $this->sendSuccess('Irrigation Way deleted successfully');
    }
}
