<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChemicalDetailAPIRequest;
use App\Http\Requests\API\UpdateChemicalDetailAPIRequest;
use App\Models\ChemicalDetail;
use App\Repositories\ChemicalDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ChemicalDetailResource;
use Response;

/**
 * Class ChemicalDetailController
 * @package App\Http\Controllers\API
 */

class ChemicalDetailAPIController extends AppBaseController
{
    /** @var  ChemicalDetailRepository */
    private $chemicalDetailRepository;

    public function __construct(ChemicalDetailRepository $chemicalDetailRepo)
    {
        $this->chemicalDetailRepository = $chemicalDetailRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/chemicalDetails",
     *      summary="Get a listing of the ChemicalDetails.",
     *      tags={"ChemicalDetail"},
     *      description="Get all ChemicalDetails",
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
     *                  @SWG\Items(ref="#/definitions/ChemicalDetail")
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
        $chemicalDetails = $this->chemicalDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => ChemicalDetailResource::collection($chemicalDetails)], 'Chemical Details retrieved successfully');
    }

    /**
     * @param CreateChemicalDetailAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/chemicalDetails",
     *      summary="Store a newly created ChemicalDetail in storage",
     *      tags={"ChemicalDetail"},
     *      description="Store ChemicalDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ChemicalDetail that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ChemicalDetail")
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
     *                  ref="#/definitions/ChemicalDetail"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateChemicalDetailAPIRequest $request)
    {
        $input = $request->validated();

        $chemicalDetail = $this->chemicalDetailRepository->save_localized($input);

        return $this->sendResponse(new ChemicalDetailResource($chemicalDetail), 'Chemical Detail saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/chemicalDetails/{id}",
     *      summary="Display the specified ChemicalDetail",
     *      tags={"ChemicalDetail"},
     *      description="Get ChemicalDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ChemicalDetail",
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
     *                  ref="#/definitions/ChemicalDetail"
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
        /** @var ChemicalDetail $chemicalDetail */
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            return $this->sendError('Chemical Detail not found');
        }

        return $this->sendResponse(new ChemicalDetailResource($chemicalDetail), 'Chemical Detail retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateChemicalDetailAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/chemicalDetails/{id}",
     *      summary="Update the specified ChemicalDetail in storage",
     *      tags={"ChemicalDetail"},
     *      description="Update ChemicalDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ChemicalDetail",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ChemicalDetail that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ChemicalDetail")
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
     *                  ref="#/definitions/ChemicalDetail"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateChemicalDetailAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ChemicalDetail $chemicalDetail */
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            return $this->sendError('Chemical Detail not found');
        }

        $chemicalDetail = $this->chemicalDetailRepository->save_localized($input, $id);

        return $this->sendResponse(new ChemicalDetailResource($chemicalDetail), 'ChemicalDetail updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/chemicalDetails/{id}",
     *      summary="Remove the specified ChemicalDetail from storage",
     *      tags={"ChemicalDetail"},
     *      description="Delete ChemicalDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ChemicalDetail",
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
        /** @var ChemicalDetail $chemicalDetail */
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            return $this->sendError('Chemical Detail not found');
        }

        $chemicalDetail->delete();

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
