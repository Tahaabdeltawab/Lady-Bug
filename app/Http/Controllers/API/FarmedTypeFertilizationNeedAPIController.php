<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeFertilizationNeedAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeFertilizationNeedAPIRequest;
use App\Models\FarmedTypeFertilizationNeed;
use App\Repositories\FarmedTypeFertilizationNeedRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeFertilizationNeedResource;
use Response;

/**
 * Class FarmedTypeFertilizationNeedController
 * @package App\Http\Controllers\API
 */

class FarmedTypeFertilizationNeedAPIController extends AppBaseController
{
    /** @var  FarmedTypeFertilizationNeedRepository */
    private $farmedTypeFertilizationNeedRepository;

    public function __construct(FarmedTypeFertilizationNeedRepository $farmedTypeFertilizationNeedRepo)
    {
        $this->farmedTypeFertilizationNeedRepository = $farmedTypeFertilizationNeedRepo;
    }

    /**
     * Display a listing of the FarmedTypeFertilizationNeed.
     * GET|HEAD /farmedTypeFertilizationNeeds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeFertilizationNeeds = $this->farmedTypeFertilizationNeedRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FarmedTypeFertilizationNeedResource::collection($farmedTypeFertilizationNeeds), 'Farmed Type Fertilization Needs retrieved successfully');
    }

    /**
     * Store a newly created FarmedTypeFertilizationNeed in storage.
     * POST /farmedTypeFertilizationNeeds
     *
     * @param CreateFarmedTypeFertilizationNeedAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeFertilizationNeedAPIRequest $request)
    {
        $input = $request->all();

        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->create($input);

        return $this->sendResponse(new FarmedTypeFertilizationNeedResource($farmedTypeFertilizationNeed), 'Farmed Type Fertilization Need saved successfully');
    }

    /**
     * Display the specified FarmedTypeFertilizationNeed.
     * GET|HEAD /farmedTypeFertilizationNeeds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FarmedTypeFertilizationNeed $farmedTypeFertilizationNeed */
        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->find($id);

        if (empty($farmedTypeFertilizationNeed)) {
            return $this->sendError('Farmed Type Fertilization Need not found');
        }

        return $this->sendResponse(new FarmedTypeFertilizationNeedResource($farmedTypeFertilizationNeed), 'Farmed Type Fertilization Need retrieved successfully');
    }

    /**
     * Update the specified FarmedTypeFertilizationNeed in storage.
     * PUT/PATCH /farmedTypeFertilizationNeeds/{id}
     *
     * @param int $id
     * @param UpdateFarmedTypeFertilizationNeedAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeFertilizationNeedAPIRequest $request)
    {
        $input = $request->all();

        /** @var FarmedTypeFertilizationNeed $farmedTypeFertilizationNeed */
        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->find($id);

        if (empty($farmedTypeFertilizationNeed)) {
            return $this->sendError('Farmed Type Fertilization Need not found');
        }

        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->update($input, $id);

        return $this->sendResponse(new FarmedTypeFertilizationNeedResource($farmedTypeFertilizationNeed), 'FarmedTypeFertilizationNeed updated successfully');
    }

    /**
     * Remove the specified FarmedTypeFertilizationNeed from storage.
     * DELETE /farmedTypeFertilizationNeeds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FarmedTypeFertilizationNeed $farmedTypeFertilizationNeed */
        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->find($id);

        if (empty($farmedTypeFertilizationNeed)) {
            return $this->sendError('Farmed Type Fertilization Need not found');
        }

        $farmedTypeFertilizationNeed->delete();

        return $this->sendSuccess('Farmed Type Fertilization Need deleted successfully');
    }
}
