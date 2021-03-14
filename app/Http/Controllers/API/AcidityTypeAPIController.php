<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAcidityTypeAPIRequest;
use App\Http\Requests\API\UpdateAcidityTypeAPIRequest;
use App\Models\AcidityType;
use App\Repositories\AcidityTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AcidityTypeResource;
use Response;

/**
 * Class AcidityTypeController
 * @package App\Http\Controllers\API
 */

class AcidityTypeAPIController extends AppBaseController
{
    /** @var  AcidityTypeRepository */
    private $acidityTypeRepository;

    public function __construct(AcidityTypeRepository $acidityTypeRepo)
    {
        $this->acidityTypeRepository = $acidityTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/acidityTypes",
     *      summary="Get a listing of the AcidityTypes.",
     *      tags={"AcidityType"},
     *      description="Get all AcidityTypes",
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
     *                  @SWG\Items(ref="#/definitions/AcidityType")
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
        $acidityTypes = $this->acidityTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AcidityTypeResource::collection($acidityTypes), 'Acidity Types retrieved successfully');
    }

    /**
     * @param CreateAcidityTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/acidityTypes",
     *      summary="Store a newly created AcidityType in storage",
     *      tags={"AcidityType"},
     *      description="Store AcidityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AcidityType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AcidityType")
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
     *                  ref="#/definitions/AcidityType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAcidityTypeAPIRequest $request)
    {
        $input = $request->all();

        $acidityType = $this->acidityTypeRepository->save_localized($input);

        return $this->sendResponse(new AcidityTypeResource($acidityType), 'Acidity Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/acidityTypes/{id}",
     *      summary="Display the specified AcidityType",
     *      tags={"AcidityType"},
     *      description="Get AcidityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AcidityType",
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
     *                  ref="#/definitions/AcidityType"
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
        /** @var AcidityType $acidityType */
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            return $this->sendError('Acidity Type not found');
        }

        return $this->sendResponse(new AcidityTypeResource($acidityType), 'Acidity Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAcidityTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/acidityTypes/{id}",
     *      summary="Update the specified AcidityType in storage",
     *      tags={"AcidityType"},
     *      description="Update AcidityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AcidityType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AcidityType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AcidityType")
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
     *                  ref="#/definitions/AcidityType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateAcidityTypeAPIRequest $request)
    {
        $input = $request->all();

        /** @var AcidityType $acidityType */
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            return $this->sendError('Acidity Type not found');
        }

        $acidityType = $this->acidityTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new AcidityTypeResource($acidityType), 'AcidityType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/acidityTypes/{id}",
     *      summary="Remove the specified AcidityType from storage",
     *      tags={"AcidityType"},
     *      description="Delete AcidityType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AcidityType",
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
        /** @var AcidityType $acidityType */
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            return $this->sendError('Acidity Type not found');
        }

        $acidityType->delete();

        return $this->sendSuccess('Acidity Type deleted successfully');
    }
}
