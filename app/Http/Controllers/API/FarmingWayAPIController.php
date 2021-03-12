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
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmingWays",
     *      summary="Get a listing of the FarmingWays.",
     *      tags={"FarmingWay"},
     *      description="Get all FarmingWays",
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
     *                  @SWG\Items(ref="#/definitions/FarmingWay")
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
        $farmingWays = $this->farmingWayRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmingWayResource::collection($farmingWays)], 'Farming Ways retrieved successfully');
    }

    /**
     * @param CreateFarmingWayAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/farmingWays",
     *      summary="Store a newly created FarmingWay in storage",
     *      tags={"FarmingWay"},
     *      description="Store FarmingWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmingWay that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmingWay")
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
     *                  ref="#/definitions/FarmingWay"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFarmingWayAPIRequest $request)
    {
        $input = $request->validated();

        $farmingWay = $this->farmingWayRepository->save_localized($input);

        return $this->sendResponse(new FarmingWayResource($farmingWay), 'Farming Way saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmingWays/{id}",
     *      summary="Display the specified FarmingWay",
     *      tags={"FarmingWay"},
     *      description="Get FarmingWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmingWay",
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
     *                  ref="#/definitions/FarmingWay"
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
        /** @var FarmingWay $farmingWay */
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            return $this->sendError('Farming Way not found');
        }

        return $this->sendResponse(new FarmingWayResource($farmingWay), 'Farming Way retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFarmingWayAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/farmingWays/{id}",
     *      summary="Update the specified FarmingWay in storage",
     *      tags={"FarmingWay"},
     *      description="Update FarmingWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmingWay",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmingWay that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmingWay")
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
     *                  ref="#/definitions/FarmingWay"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFarmingWayAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmingWay $farmingWay */
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            return $this->sendError('Farming Way not found');
        }

        $farmingWay = $this->farmingWayRepository->save_localized($input, $id);

        return $this->sendResponse(new FarmingWayResource($farmingWay), 'FarmingWay updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/farmingWays/{id}",
     *      summary="Remove the specified FarmingWay from storage",
     *      tags={"FarmingWay"},
     *      description="Delete FarmingWay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmingWay",
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
        /** @var FarmingWay $farmingWay */
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            return $this->sendError('Farming Way not found');
        }

        $farmingWay->delete();

        return $this->sendSuccess('Farming Way deleted successfully');
    }
}
