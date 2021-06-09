<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateInformationAPIRequest;
use App\Http\Requests\API\UpdateInformationAPIRequest;
use App\Models\Information;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\InformationResource;
use Response;

/**
 * Class InformationController
 * @package App\Http\Controllers\API
 */

class InformationAPIController extends AppBaseController
{
    /** @var  InformationRepository */
    private $informationRepository;

    public function __construct(InformationRepository $informationRepo)
    {
        $this->informationRepository = $informationRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/information",
     *      summary="Get a listing of the Information.",
     *      tags={"Information"},
     *      description="Get all Information",
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
     *                  @SWG\Items(ref="#/definitions/Information")
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
        $information = $this->informationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => InformationResource::collection($information)], 'Information retrieved successfully');
    }

    /**
     * @param CreateInformationAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/information",
     *      summary="Store a newly created Information in storage",
     *      tags={"Information"},
     *      description="Store Information",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Information that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Information")
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
     *                  ref="#/definitions/Information"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateInformationAPIRequest $request)
    {
        $input = $request->validated();

        $information = $this->informationRepository->save_localized($input);

        return $this->sendResponse(new InformationResource($information), 'Information saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/information/{id}",
     *      summary="Display the specified Information",
     *      tags={"Information"},
     *      description="Get Information",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Information",
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
     *                  ref="#/definitions/Information"
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
        /** @var Information $information */
        $information = $this->informationRepository->find($id);

        if (empty($information)) {
            return $this->sendError('Information not found');
        }

        return $this->sendResponse(new InformationResource($information), 'Information retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateInformationAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/information/{id}",
     *      summary="Update the specified Information in storage",
     *      tags={"Information"},
     *      description="Update Information",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Information",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Information that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Information")
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
     *                  ref="#/definitions/Information"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateInformationAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Information $information */
        $information = $this->informationRepository->find($id);

        if (empty($information)) {
            return $this->sendError('Information not found');
        }

        $information = $this->informationRepository->save_localized($input, $id);

        return $this->sendResponse(new InformationResource($information), 'Information updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/information/{id}",
     *      summary="Remove the specified Information from storage",
     *      tags={"Information"},
     *      description="Delete Information",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Information",
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
        /** @var Information $information */
        $information = $this->informationRepository->find($id);

        if (empty($information)) {
            return $this->sendError('Information not found');
        }

        $information->delete();

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
