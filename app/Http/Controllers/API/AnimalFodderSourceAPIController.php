<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAnimalFodderSourceAPIRequest;
use App\Http\Requests\API\UpdateAnimalFodderSourceAPIRequest;
use App\Models\AnimalFodderSource;
use App\Repositories\AnimalFodderSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AnimalFodderSourceResource;
use Response;

/**
 * Class AnimalFodderSourceController
 * @package App\Http\Controllers\API
 */

class AnimalFodderSourceAPIController extends AppBaseController
{
    /** @var  AnimalFodderSourceRepository */
    private $animalFodderSourceRepository;

    public function __construct(AnimalFodderSourceRepository $animalFodderSourceRepo)
    {
        $this->animalFodderSourceRepository = $animalFodderSourceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalFodderSources",
     *      summary="Get a listing of the AnimalFodderSources.",
     *      tags={"AnimalFodderSource"},
     *      description="Get all AnimalFodderSources",
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
     *                  @SWG\Items(ref="#/definitions/AnimalFodderSource")
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
        $animalFodderSources = $this->animalFodderSourceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => AnimalFodderSourceResource::collection($animalFodderSources)], 'Animal Fodder Sources retrieved successfully');
    }

    /**
     * @param CreateAnimalFodderSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/animalFodderSources",
     *      summary="Store a newly created AnimalFodderSource in storage",
     *      tags={"AnimalFodderSource"},
     *      description="Store AnimalFodderSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalFodderSource that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalFodderSource")
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
     *                  ref="#/definitions/AnimalFodderSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAnimalFodderSourceAPIRequest $request)
    {
        $input = $request->validated();

        $animalFodderSource = $this->animalFodderSourceRepository->save_localized($input);

        return $this->sendResponse(new AnimalFodderSourceResource($animalFodderSource), 'Animal Fodder Source saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalFodderSources/{id}",
     *      summary="Display the specified AnimalFodderSource",
     *      tags={"AnimalFodderSource"},
     *      description="Get AnimalFodderSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalFodderSource",
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
     *                  ref="#/definitions/AnimalFodderSource"
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
        /** @var AnimalFodderSource $animalFodderSource */
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            return $this->sendError('Animal Fodder Source not found');
        }

        return $this->sendResponse(new AnimalFodderSourceResource($animalFodderSource), 'Animal Fodder Source retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAnimalFodderSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/animalFodderSources/{id}",
     *      summary="Update the specified AnimalFodderSource in storage",
     *      tags={"AnimalFodderSource"},
     *      description="Update AnimalFodderSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalFodderSource",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalFodderSource that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalFodderSource")
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
     *                  ref="#/definitions/AnimalFodderSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateAnimalFodderSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var AnimalFodderSource $animalFodderSource */
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            return $this->sendError('Animal Fodder Source not found');
        }

        $animalFodderSource = $this->animalFodderSourceRepository->save_localized($input, $id);

        return $this->sendResponse(new AnimalFodderSourceResource($animalFodderSource), 'AnimalFodderSource updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/animalFodderSources/{id}",
     *      summary="Remove the specified AnimalFodderSource from storage",
     *      tags={"AnimalFodderSource"},
     *      description="Delete AnimalFodderSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalFodderSource",
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
        /** @var AnimalFodderSource $animalFodderSource */
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            return $this->sendError('Animal Fodder Source not found');
        }

        $animalFodderSource->delete();

        return $this->sendSuccess('Animal Fodder Source deleted successfully');
    }
}
