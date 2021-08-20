<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSaltTypeAPIRequest;
use App\Http\Requests\API\UpdateSaltTypeAPIRequest;
use App\Models\SaltType;
use App\Repositories\SaltTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SaltTypeResource;
use Response;

/**
 * Class SaltTypeController
 * @package App\Http\Controllers\API
 */

class SaltTypeAPIController extends AppBaseController
{
    /** @var  SaltTypeRepository */
    private $saltTypeRepository;

    public function __construct(SaltTypeRepository $saltTypeRepo)
    {
        $this->saltTypeRepository = $saltTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/saltTypes",
     *      summary="Get a listing of the SaltTypes.",
     *      tags={"SaltType"},
     *      description="Get all SaltTypes",
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
     *                  @SWG\Items(ref="#/definitions/SaltType")
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
        $saltTypes = $this->saltTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SaltTypeResource::collection($saltTypes), 'Salt Types retrieved successfully');
    }

    /**
     * @param CreateSaltTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/saltTypes",
     *      summary="Store a newly created SaltType in storage",
     *      tags={"SaltType"},
     *      description="Store SaltType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SaltType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SaltType")
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
     *                  ref="#/definitions/SaltType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateSaltTypeAPIRequest $request)
    {
        $input = $request->validated();

        $saltType = $this->saltTypeRepository->save_localized($input);

        return $this->sendResponse(new SaltTypeResource($saltType), 'Salt Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/saltTypes/{id}",
     *      summary="Display the specified SaltType",
     *      tags={"SaltType"},
     *      description="Get SaltType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SaltType",
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
     *                  ref="#/definitions/SaltType"
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
        /** @var SaltType $saltType */
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            return $this->sendError('Salt Type not found');
        }

        return $this->sendResponse(new SaltTypeResource($saltType), 'Salt Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSaltTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/saltTypes/{id}",
     *      summary="Update the specified SaltType in storage",
     *      tags={"SaltType"},
     *      description="Update SaltType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SaltType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SaltType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SaltType")
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
     *                  ref="#/definitions/SaltType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateSaltTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SaltType $saltType */
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            return $this->sendError('Salt Type not found');
        }

        $saltType = $this->saltTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new SaltTypeResource($saltType), 'SaltType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/saltTypes/{id}",
     *      summary="Remove the specified SaltType from storage",
     *      tags={"SaltType"},
     *      description="Delete SaltType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SaltType",
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
        /** @var SaltType $saltType */
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            return $this->sendError('Salt Type not found');
        }

        $saltType->delete();

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
