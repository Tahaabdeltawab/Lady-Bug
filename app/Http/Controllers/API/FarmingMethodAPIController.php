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
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmingMethods",
     *      summary="Get a listing of the FarmingMethods.",
     *      tags={"FarmingMethod"},
     *      description="Get all FarmingMethods",
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
     *                  @SWG\Items(ref="#/definitions/FarmingMethod")
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
        $farmingMethods = $this->farmingMethodRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmingMethodResource::collection($farmingMethods)], 'Farming Methods retrieved successfully');
    }

    /**
     * @param CreateFarmingMethodAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/farmingMethods",
     *      summary="Store a newly created FarmingMethod in storage",
     *      tags={"FarmingMethod"},
     *      description="Store FarmingMethod",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmingMethod that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmingMethod")
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
     *                  ref="#/definitions/FarmingMethod"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFarmingMethodAPIRequest $request)
    {
        $input = $request->validated();

        $farmingMethod = $this->farmingMethodRepository->save_localized($input);

        return $this->sendResponse(new FarmingMethodResource($farmingMethod), 'Farming Method saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmingMethods/{id}",
     *      summary="Display the specified FarmingMethod",
     *      tags={"FarmingMethod"},
     *      description="Get FarmingMethod",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmingMethod",
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
     *                  ref="#/definitions/FarmingMethod"
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
        /** @var FarmingMethod $farmingMethod */
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            return $this->sendError('Farming Method not found');
        }

        return $this->sendResponse(new FarmingMethodResource($farmingMethod), 'Farming Method retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFarmingMethodAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/farmingMethods/{id}",
     *      summary="Update the specified FarmingMethod in storage",
     *      tags={"FarmingMethod"},
     *      description="Update FarmingMethod",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmingMethod",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmingMethod that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmingMethod")
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
     *                  ref="#/definitions/FarmingMethod"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/farmingMethods/{id}",
     *      summary="Remove the specified FarmingMethod from storage",
     *      tags={"FarmingMethod"},
     *      description="Delete FarmingMethod",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmingMethod",
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
        /** @var FarmingMethod $farmingMethod */
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            return $this->sendError('Farming Method not found');
        }

        $farmingMethod->delete();

        return $this->sendSuccess('Farming Method deleted successfully');
    }
}
