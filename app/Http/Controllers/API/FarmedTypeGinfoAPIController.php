<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeGinfoAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeGinfoAPIRequest;
use App\Models\FarmedTypeGinfo;
use App\Repositories\FarmedTypeGinfoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeGinfoResource;
use Response;

/**
 * Class FarmedTypeGinfoController
 * @package App\Http\Controllers\API
 */

class FarmedTypeGinfoAPIController extends AppBaseController
{
    /** @var  FarmedTypeGinfoRepository */
    private $farmedTypeGinfoRepository;

    public function __construct(FarmedTypeGinfoRepository $farmedTypeGinfoRepo)
    {
        $this->farmedTypeGinfoRepository = $farmedTypeGinfoRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypeGinfos",
     *      summary="Get a listing of the FarmedTypeGinfos.",
     *      tags={"FarmedTypeGinfo"},
     *      description="Get all FarmedTypeGinfos",
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
     *                  @SWG\Items(ref="#/definitions/FarmedTypeGinfo")
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
        $farmedTypeGinfos = $this->farmedTypeGinfoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeGinfoResource::collection($farmedTypeGinfos)], 'Farmed Type Ginfos retrieved successfully');
    }

    /**
     * @param CreateFarmedTypeGinfoAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/farmedTypeGinfos",
     *      summary="Store a newly created FarmedTypeGinfo in storage",
     *      tags={"FarmedTypeGinfo"},
     *      description="Store FarmedTypeGinfo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedTypeGinfo that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedTypeGinfo")
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
     *                  ref="#/definitions/FarmedTypeGinfo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFarmedTypeGinfoAPIRequest $request)
    {
        $input = $request->validated();

        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->save_localized($input);

        return $this->sendResponse(new FarmedTypeGinfoResource($farmedTypeGinfo), 'Farmed Type Ginfo saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypeGinfos/{id}",
     *      summary="Display the specified FarmedTypeGinfo",
     *      tags={"FarmedTypeGinfo"},
     *      description="Get FarmedTypeGinfo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeGinfo",
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
     *                  ref="#/definitions/FarmedTypeGinfo"
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
        /** @var FarmedTypeGinfo $farmedTypeGinfo */
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            return $this->sendError('Farmed Type Ginfo not found');
        }

        return $this->sendResponse(new FarmedTypeGinfoResource($farmedTypeGinfo), 'Farmed Type Ginfo retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFarmedTypeGinfoAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/farmedTypeGinfos/{id}",
     *      summary="Update the specified FarmedTypeGinfo in storage",
     *      tags={"FarmedTypeGinfo"},
     *      description="Update FarmedTypeGinfo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeGinfo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedTypeGinfo that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedTypeGinfo")
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
     *                  ref="#/definitions/FarmedTypeGinfo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateFarmedTypeGinfoAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeGinfo $farmedTypeGinfo */
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            return $this->sendError('Farmed Type Ginfo not found');
        }

        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->save_localized($input, $id);

        return $this->sendResponse(new FarmedTypeGinfoResource($farmedTypeGinfo), 'FarmedTypeGinfo updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/farmedTypeGinfos/{id}",
     *      summary="Remove the specified FarmedTypeGinfo from storage",
     *      tags={"FarmedTypeGinfo"},
     *      description="Delete FarmedTypeGinfo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeGinfo",
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
        /** @var FarmedTypeGinfo $farmedTypeGinfo */
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            return $this->sendError('Farmed Type Ginfo not found');
        }

        $farmedTypeGinfo->delete();

        return $this->sendSuccess('Farmed Type Ginfo deleted successfully');
    }
}
