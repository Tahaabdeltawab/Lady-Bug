<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDistrictAPIRequest;
use App\Http\Requests\API\UpdateDistrictAPIRequest;
use App\Models\District;
use App\Repositories\DistrictRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DistrictResource;
use Response;

/**
 * Class DistrictController
 * @package App\Http\Controllers\API
 */

class DistrictAPIController extends AppBaseController
{
    /** @var  DistrictRepository */
    private $districtRepository;

    public function __construct(DistrictRepository $districtRepo)
    {
        $this->districtRepository = $districtRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/districts",
     *      summary="Get a listing of the Districts.",
     *      tags={"District"},
     *      description="Get all Districts",
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
     *                  @SWG\Items(ref="#/definitions/District")
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
        $districts = $this->districtRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => DistrictResource::collection($districts)], 'Districts retrieved successfully');
    }

    /**
     * @param CreateDistrictAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/districts",
     *      summary="Store a newly created District in storage",
     *      tags={"District"},
     *      description="Store District",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="District that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/District")
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
     *                  ref="#/definitions/District"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateDistrictAPIRequest $request)
    {
        $input = $request->validated();

        $district = $this->districtRepository->save_localized($input);

        return $this->sendResponse(new DistrictResource($district), 'District saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/districts/{id}",
     *      summary="Display the specified District",
     *      tags={"District"},
     *      description="Get District",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of District",
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
     *                  ref="#/definitions/District"
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
        /** @var District $district */
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            return $this->sendError('District not found');
        }

        return $this->sendResponse(new DistrictResource($district), 'District retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateDistrictAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/districts/{id}",
     *      summary="Update the specified District in storage",
     *      tags={"District"},
     *      description="Update District",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of District",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="District that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/District")
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
     *                  ref="#/definitions/District"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateDistrictAPIRequest $request)
    {
        $input = $request->validated();

        /** @var District $district */
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            return $this->sendError('District not found');
        }

        $district = $this->districtRepository->save_localized($input, $id);

        return $this->sendResponse(new DistrictResource($district), 'District updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/districts/{id}",
     *      summary="Remove the specified District from storage",
     *      tags={"District"},
     *      description="Delete District",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of District",
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
        /** @var District $district */
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            return $this->sendError('District not found');
        }

        $district->delete();

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
