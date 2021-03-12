<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmActivityTypeAPIRequest;
use App\Http\Requests\API\UpdateFarmActivityTypeAPIRequest;
use App\Models\FarmActivityType;
use App\Repositories\FarmActivityTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmActivityTypeResource;
use Response;

/**
 * Class FarmActivityTypeController
 * @package App\Http\Controllers\API
 */

class FarmActivityTypeAPIController extends AppBaseController
{
    /** @var  FarmActivityTypeRepository */
    private $farmActivityTypeRepository;

    public function __construct(FarmActivityTypeRepository $farmActivityTypeRepo)
    {
        $this->farmActivityTypeRepository = $farmActivityTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmActivityTypes",
     *      summary="Get a listing of the FarmActivityTypes.",
     *      tags={"FarmActivityType"},
     *      description="Get all FarmActivityTypes",
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
     *                  @SWG\Items(ref="#/definitions/FarmActivityType")
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
        $farmActivityTypes = $this->farmActivityTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmActivityTypeResource::collection($farmActivityTypes)], 'Farm Activity Types retrieved successfully');
    }

    /**
     * @param CreateFarmActivityTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/farmActivityTypes",
     *      summary="Store a newly created FarmActivityType in storage",
     *      tags={"FarmActivityType"},
     *      description="Store FarmActivityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmActivityType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmActivityType")
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
     *                  ref="#/definitions/FarmActivityType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFarmActivityTypeAPIRequest $request)
    {
        $input = $request->validated();

        $farmActivityType = $this->farmActivityTypeRepository->save_localized($input);
        // $farmActivityType = $this->farmActivityTypeRepository->save_localized($input);

        return $this->sendResponse(new FarmActivityTypeResource($farmActivityType), 'Farm Activity Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmActivityTypes/{id}",
     *      summary="Display the specified FarmActivityType",
     *      tags={"FarmActivityType"},
     *      description="Get FarmActivityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmActivityType",
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
     *                  ref="#/definitions/FarmActivityType"
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
        /** @var FarmActivityType $farmActivityType */
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            return $this->sendError('Farm Activity Type not found');
        }

        return $this->sendResponse(new FarmActivityTypeResource($farmActivityType), 'Farm Activity Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFarmActivityTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/farmActivityTypes/{id}",
     *      summary="Update the specified FarmActivityType in storage",
     *      tags={"FarmActivityType"},
     *      description="Update FarmActivityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmActivityType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmActivityType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmActivityType")
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
     *                  ref="#/definitions/FarmActivityType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFarmActivityTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmActivityType $farmActivityType */
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            return $this->sendError('Farm Activity Type not found');
        }

        $farmActivityType = $this->farmActivityTypeRepository->save_localized($input, $id);
        // $farmActivityType = $this->farmActivityTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new FarmActivityTypeResource($farmActivityType), 'FarmActivityType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/farmActivityTypes/{id}",
     *      summary="Remove the specified FarmActivityType from storage",
     *      tags={"FarmActivityType"},
     *      description="Delete FarmActivityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmActivityType",
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
        /** @var FarmActivityType $farmActivityType */
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            return $this->sendError('Farm Activity Type not found');
        }

        $farmActivityType->delete();

        return $this->sendSuccess('Farm Activity Type deleted successfully');
    }
}
