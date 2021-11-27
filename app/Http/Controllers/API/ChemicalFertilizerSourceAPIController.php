<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChemicalFertilizerSourceAPIRequest;
use App\Http\Requests\API\UpdateChemicalFertilizerSourceAPIRequest;
use App\Models\ChemicalFertilizerSource;
use App\Repositories\ChemicalFertilizerSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ChemicalFertilizerSourceResource;
use Response;

/**
 * Class ChemicalFertilizerSourceController
 * @package App\Http\Controllers\API
 */

class ChemicalFertilizerSourceAPIController extends AppBaseController
{
    /** @var  ChemicalFertilizerSourceRepository */
    private $chemicalFertilizerSourceRepository;

    public function __construct(ChemicalFertilizerSourceRepository $chemicalFertilizerSourceRepo)
    {
        $this->chemicalFertilizerSourceRepository = $chemicalFertilizerSourceRepo;

        $this->middleware('permission:chemical_fertilizer_sources.store')->only(['store']);
        $this->middleware('permission:chemical_fertilizer_sources.update')->only(['update']);
        $this->middleware('permission:chemical_fertilizer_sources.destroy')->only(['destroy']);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/chemicalFertilizerSources",
     *      summary="Get a listing of the ChemicalFertilizerSources.",
     *      tags={"ChemicalFertilizerSource"},
     *      description="Get all ChemicalFertilizerSources",
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
     *                  @SWG\Items(ref="#/definitions/ChemicalFertilizerSource")
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
        $chemicalFertilizerSources = $this->chemicalFertilizerSourceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => ChemicalFertilizerSourceResource::collection($chemicalFertilizerSources)], 'Chemical Fertilizer Sources retrieved successfully');
    }

    /**
     * @param CreateChemicalFertilizerSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/chemicalFertilizerSources",
     *      summary="Store a newly created ChemicalFertilizerSource in storage",
     *      tags={"ChemicalFertilizerSource"},
     *      description="Store ChemicalFertilizerSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ChemicalFertilizerSource that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ChemicalFertilizerSource")
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
     *                  ref="#/definitions/ChemicalFertilizerSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateChemicalFertilizerSourceAPIRequest $request)
    {
        $input = $request->validated();

        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->save_localized($input);

        return $this->sendResponse(new ChemicalFertilizerSourceResource($chemicalFertilizerSource), 'Chemical Fertilizer Source saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/chemicalFertilizerSources/{id}",
     *      summary="Display the specified ChemicalFertilizerSource",
     *      tags={"ChemicalFertilizerSource"},
     *      description="Get ChemicalFertilizerSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ChemicalFertilizerSource",
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
     *                  ref="#/definitions/ChemicalFertilizerSource"
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
        /** @var ChemicalFertilizerSource $chemicalFertilizerSource */
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            return $this->sendError('Chemical Fertilizer Source not found');
        }

        return $this->sendResponse(new ChemicalFertilizerSourceResource($chemicalFertilizerSource), 'Chemical Fertilizer Source retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateChemicalFertilizerSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/chemicalFertilizerSources/{id}",
     *      summary="Update the specified ChemicalFertilizerSource in storage",
     *      tags={"ChemicalFertilizerSource"},
     *      description="Update ChemicalFertilizerSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ChemicalFertilizerSource",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ChemicalFertilizerSource that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ChemicalFertilizerSource")
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
     *                  ref="#/definitions/ChemicalFertilizerSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateChemicalFertilizerSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ChemicalFertilizerSource $chemicalFertilizerSource */
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            return $this->sendError('Chemical Fertilizer Source not found');
        }

        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->save_localized($input, $id);

        return $this->sendResponse(new ChemicalFertilizerSourceResource($chemicalFertilizerSource), 'ChemicalFertilizerSource updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/chemicalFertilizerSources/{id}",
     *      summary="Remove the specified ChemicalFertilizerSource from storage",
     *      tags={"ChemicalFertilizerSource"},
     *      description="Delete ChemicalFertilizerSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ChemicalFertilizerSource",
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
        /** @var ChemicalFertilizerSource $chemicalFertilizerSource */
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            return $this->sendError('Chemical Fertilizer Source not found');
        }

        $chemicalFertilizerSource->delete();

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
