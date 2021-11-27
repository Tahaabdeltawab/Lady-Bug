<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSoilTypeAPIRequest;
use App\Http\Requests\API\UpdateSoilTypeAPIRequest;
use App\Models\SoilType;
use App\Repositories\SoilTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SoilTypeResource;
use Response;

/**
 * Class SoilTypeController
 * @package App\Http\Controllers\API
 */

class SoilTypeAPIController extends AppBaseController
{
    /** @var  SoilTypeRepository */
    private $soilTypeRepository;

    public function __construct(SoilTypeRepository $soilTypeRepo)
    {
        $this->soilTypeRepository = $soilTypeRepo;

        $this->middleware('permission:soil_types.store')->only(['store']);
        $this->middleware('permission:soil_types.update')->only(['update']);
        $this->middleware('permission:soil_types.destroy')->only(['destroy']);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/soilTypes",
     *      summary="Get a listing of the SoilTypes.",
     *      tags={"SoilType"},
     *      description="Get all SoilTypes",
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
     *                  @SWG\Items(ref="#/definitions/SoilType")
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
        $soilTypes = $this->soilTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => SoilTypeResource::collection($soilTypes)], 'Soil Types retrieved successfully');
    }

    /**
     * @param CreateSoilTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/soilTypes",
     *      summary="Store a newly created SoilType in storage",
     *      tags={"SoilType"},
     *      description="Store SoilType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SoilType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SoilType")
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
     *                  ref="#/definitions/SoilType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateSoilTypeAPIRequest $request)
    {
        $input = $request->validated();

        $soilType = $this->soilTypeRepository->save_localized($input);

        return $this->sendResponse(new SoilTypeResource($soilType), 'Soil Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/soilTypes/{id}",
     *      summary="Display the specified SoilType",
     *      tags={"SoilType"},
     *      description="Get SoilType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SoilType",
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
     *                  ref="#/definitions/SoilType"
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
        /** @var SoilType $soilType */
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            return $this->sendError('Soil Type not found');
        }

        return $this->sendResponse(new SoilTypeResource($soilType), 'Soil Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSoilTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/soilTypes/{id}",
     *      summary="Update the specified SoilType in storage",
     *      tags={"SoilType"},
     *      description="Update SoilType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SoilType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SoilType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SoilType")
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
     *                  ref="#/definitions/SoilType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateSoilTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SoilType $soilType */
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            return $this->sendError('Soil Type not found');
        }

        $soilType = $this->soilTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new SoilTypeResource($soilType), 'SoilType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/soilTypes/{id}",
     *      summary="Remove the specified SoilType from storage",
     *      tags={"SoilType"},
     *      description="Delete SoilType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SoilType",
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
        /** @var SoilType $soilType */
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            return $this->sendError('Soil Type not found');
        }

        $soilType->delete();

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
