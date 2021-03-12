<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHomePlantIlluminatingSourceAPIRequest;
use App\Http\Requests\API\UpdateHomePlantIlluminatingSourceAPIRequest;
use App\Models\HomePlantIlluminatingSource;
use App\Repositories\HomePlantIlluminatingSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\HomePlantIlluminatingSourceResource;
use Response;

/**
 * Class HomePlantIlluminatingSourceController
 * @package App\Http\Controllers\API
 */

class HomePlantIlluminatingSourceAPIController extends AppBaseController
{
    /** @var  HomePlantIlluminatingSourceRepository */
    private $homePlantIlluminatingSourceRepository;

    public function __construct(HomePlantIlluminatingSourceRepository $homePlantIlluminatingSourceRepo)
    {
        $this->homePlantIlluminatingSourceRepository = $homePlantIlluminatingSourceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/homePlantIlluminatingSources",
     *      summary="Get a listing of the HomePlantIlluminatingSources.",
     *      tags={"HomePlantIlluminatingSource"},
     *      description="Get all HomePlantIlluminatingSources",
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
     *                  @SWG\Items(ref="#/definitions/HomePlantIlluminatingSource")
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
        $homePlantIlluminatingSources = $this->homePlantIlluminatingSourceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => HomePlantIlluminatingSourceResource::collection($homePlantIlluminatingSources)], 'Home Plant Illuminating Sources retrieved successfully');
    }

    /**
     * @param CreateHomePlantIlluminatingSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/homePlantIlluminatingSources",
     *      summary="Store a newly created HomePlantIlluminatingSource in storage",
     *      tags={"HomePlantIlluminatingSource"},
     *      description="Store HomePlantIlluminatingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="HomePlantIlluminatingSource that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/HomePlantIlluminatingSource")
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
     *                  ref="#/definitions/HomePlantIlluminatingSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateHomePlantIlluminatingSourceAPIRequest $request)
    {
        $input = $request->validated();

        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->save_localized($input);

        return $this->sendResponse(new HomePlantIlluminatingSourceResource($homePlantIlluminatingSource), 'Home Plant Illuminating Source saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/homePlantIlluminatingSources/{id}",
     *      summary="Display the specified HomePlantIlluminatingSource",
     *      tags={"HomePlantIlluminatingSource"},
     *      description="Get HomePlantIlluminatingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of HomePlantIlluminatingSource",
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
     *                  ref="#/definitions/HomePlantIlluminatingSource"
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
        /** @var HomePlantIlluminatingSource $homePlantIlluminatingSource */
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            return $this->sendError('Home Plant Illuminating Source not found');
        }

        return $this->sendResponse(new HomePlantIlluminatingSourceResource($homePlantIlluminatingSource), 'Home Plant Illuminating Source retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateHomePlantIlluminatingSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/homePlantIlluminatingSources/{id}",
     *      summary="Update the specified HomePlantIlluminatingSource in storage",
     *      tags={"HomePlantIlluminatingSource"},
     *      description="Update HomePlantIlluminatingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of HomePlantIlluminatingSource",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="HomePlantIlluminatingSource that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/HomePlantIlluminatingSource")
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
     *                  ref="#/definitions/HomePlantIlluminatingSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateHomePlantIlluminatingSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var HomePlantIlluminatingSource $homePlantIlluminatingSource */
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            return $this->sendError('Home Plant Illuminating Source not found');
        }

        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->save_localized($input, $id);

        return $this->sendResponse(new HomePlantIlluminatingSourceResource($homePlantIlluminatingSource), 'HomePlantIlluminatingSource updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/homePlantIlluminatingSources/{id}",
     *      summary="Remove the specified HomePlantIlluminatingSource from storage",
     *      tags={"HomePlantIlluminatingSource"},
     *      description="Delete HomePlantIlluminatingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of HomePlantIlluminatingSource",
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
        /** @var HomePlantIlluminatingSource $homePlantIlluminatingSource */
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            return $this->sendError('Home Plant Illuminating Source not found');
        }

        $homePlantIlluminatingSource->delete();

        return $this->sendSuccess('Home Plant Illuminating Source deleted successfully');
    }
}
