<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSaltDetailAPIRequest;
use App\Http\Requests\API\UpdateSaltDetailAPIRequest;
use App\Models\SaltDetail;
use App\Repositories\SaltDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SaltDetailResource;
use Response;

/**
 * Class SaltDetailController
 * @package App\Http\Controllers\API
 */

class SaltDetailAPIController extends AppBaseController
{
    /** @var  SaltDetailRepository */
    private $saltDetailRepository;

    public function __construct(SaltDetailRepository $saltDetailRepo)
    {
        $this->saltDetailRepository = $saltDetailRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/saltDetails",
     *      summary="Get a listing of the SaltDetails.",
     *      tags={"SaltDetail"},
     *      description="Get all SaltDetails",
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
     *                  @SWG\Items(ref="#/definitions/SaltDetail")
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
        $saltDetails = $this->saltDetailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => SaltDetailResource::collection($saltDetails)], 'Salt Details retrieved successfully');
    }

    /**
     * @param CreateSaltDetailAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/saltDetails",
     *      summary="Store a newly created SaltDetail in storage",
     *      tags={"SaltDetail"},
     *      description="Store SaltDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SaltDetail that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SaltDetail")
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
     *                  ref="#/definitions/SaltDetail"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateSaltDetailAPIRequest $request)
    {
        $input = $request->validated();

        $saltDetail = $this->saltDetailRepository->save_localized($input);

        return $this->sendResponse(new SaltDetailResource($saltDetail), 'Salt Detail saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/saltDetails/{id}",
     *      summary="Display the specified SaltDetail",
     *      tags={"SaltDetail"},
     *      description="Get SaltDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SaltDetail",
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
     *                  ref="#/definitions/SaltDetail"
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
        /** @var SaltDetail $saltDetail */
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            return $this->sendError('Salt Detail not found');
        }

        return $this->sendResponse(new SaltDetailResource($saltDetail), 'Salt Detail retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSaltDetailAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/saltDetails/{id}",
     *      summary="Update the specified SaltDetail in storage",
     *      tags={"SaltDetail"},
     *      description="Update SaltDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SaltDetail",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SaltDetail that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SaltDetail")
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
     *                  ref="#/definitions/SaltDetail"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateSaltDetailAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SaltDetail $saltDetail */
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            return $this->sendError('Salt Detail not found');
        }

        $saltDetail = $this->saltDetailRepository->save_localized($input, $id);

        return $this->sendResponse(new SaltDetailResource($saltDetail), 'SaltDetail updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/saltDetails/{id}",
     *      summary="Remove the specified SaltDetail from storage",
     *      tags={"SaltDetail"},
     *      description="Delete SaltDetail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SaltDetail",
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
        /** @var SaltDetail $saltDetail */
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            return $this->sendError('Salt Detail not found');
        }

        $saltDetail->delete();

        return $this->sendSuccess('Salt Detail deleted successfully');
    }
}
