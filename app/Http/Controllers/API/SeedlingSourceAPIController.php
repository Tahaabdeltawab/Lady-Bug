<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSeedlingSourceAPIRequest;
use App\Http\Requests\API\UpdateSeedlingSourceAPIRequest;
use App\Models\SeedlingSource;
use App\Repositories\SeedlingSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SeedlingSourceResource;
use Response;

/**
 * Class SeedlingSourceController
 * @package App\Http\Controllers\API
 */

class SeedlingSourceAPIController extends AppBaseController
{
    /** @var  SeedlingSourceRepository */
    private $seedlingSourceRepository;

    public function __construct(SeedlingSourceRepository $seedlingSourceRepo)
    {
        $this->seedlingSourceRepository = $seedlingSourceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/seedlingSources",
     *      summary="Get a listing of the SeedlingSources.",
     *      tags={"SeedlingSource"},
     *      description="Get all SeedlingSources",
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
     *                  @SWG\Items(ref="#/definitions/SeedlingSource")
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
        $seedlingSources = $this->seedlingSourceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => SeedlingSourceResource::collection($seedlingSources)], 'Seedling Sources retrieved successfully');
    }

    /**
     * @param CreateSeedlingSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/seedlingSources",
     *      summary="Store a newly created SeedlingSource in storage",
     *      tags={"SeedlingSource"},
     *      description="Store SeedlingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SeedlingSource that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SeedlingSource")
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
     *                  ref="#/definitions/SeedlingSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateSeedlingSourceAPIRequest $request)
    {
        $input = $request->validated();

        $seedlingSource = $this->seedlingSourceRepository->save_localized($input);

        return $this->sendResponse(new SeedlingSourceResource($seedlingSource), 'Seedling Source saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/seedlingSources/{id}",
     *      summary="Display the specified SeedlingSource",
     *      tags={"SeedlingSource"},
     *      description="Get SeedlingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SeedlingSource",
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
     *                  ref="#/definitions/SeedlingSource"
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
        /** @var SeedlingSource $seedlingSource */
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            return $this->sendError('Seedling Source not found');
        }

        return $this->sendResponse(new SeedlingSourceResource($seedlingSource), 'Seedling Source retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSeedlingSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/seedlingSources/{id}",
     *      summary="Update the specified SeedlingSource in storage",
     *      tags={"SeedlingSource"},
     *      description="Update SeedlingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SeedlingSource",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="SeedlingSource that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/SeedlingSource")
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
     *                  ref="#/definitions/SeedlingSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateSeedlingSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SeedlingSource $seedlingSource */
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            return $this->sendError('Seedling Source not found');
        }

        $seedlingSource = $this->seedlingSourceRepository->save_localized($input, $id);

        return $this->sendResponse(new SeedlingSourceResource($seedlingSource), 'SeedlingSource updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/seedlingSources/{id}",
     *      summary="Remove the specified SeedlingSource from storage",
     *      tags={"SeedlingSource"},
     *      description="Delete SeedlingSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of SeedlingSource",
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
        /** @var SeedlingSource $seedlingSource */
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            return $this->sendError('Seedling Source not found');
        }

        $seedlingSource->delete();

        return $this->sendSuccess('Seedling Source deleted successfully');
    }
}
