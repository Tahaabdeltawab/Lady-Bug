<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeNutValAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeNutValAPIRequest;
use App\Models\FarmedTypeNutVal;
use App\Repositories\FarmedTypeNutValRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeNutValResource;
use Response;

/**
 * Class FarmedTypeNutValController
 * @package App\Http\Controllers\API
 */

class FarmedTypeNutValAPIController extends AppBaseController
{
    /** @var  FarmedTypeNutValRepository */
    private $farmedTypeNutValRepository;

    public function __construct(FarmedTypeNutValRepository $farmedTypeNutValRepo)
    {
        $this->farmedTypeNutValRepository = $farmedTypeNutValRepo;
    }

    /**
     * Display a listing of the FarmedTypeNutVal.
     * GET|HEAD /farmedTypeNutVals
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeNutVals = $this->farmedTypeNutValRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FarmedTypeNutValResource::collection($farmedTypeNutVals), 'Farmed Type Nut Vals retrieved successfully');
    }

    /**
     * Store a newly created FarmedTypeNutVal in storage.
     * POST /farmedTypeNutVals
     *
     * @param CreateFarmedTypeNutValAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeNutValAPIRequest $request)
    {
        $input = $request->validated();
        $farmedTypeNutVal = FarmedTypeNutVal::updateOrCreate(['farmed_type_id' => $request->farmed_type_id], $input);

        return $this->sendResponse(new FarmedTypeNutValResource($farmedTypeNutVal), 'Farmed Type Nut Val saved successfully');
    }

    /**
     * Display the specified FarmedTypeNutVal.
     * GET|HEAD /farmedTypeNutVals/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FarmedTypeNutVal $farmedTypeNutVal */
        $farmedTypeNutVal = $this->farmedTypeNutValRepository->find($id);

        if (empty($farmedTypeNutVal)) {
            return $this->sendError('Farmed Type Nut Val not found');
        }

        return $this->sendResponse(new FarmedTypeNutValResource($farmedTypeNutVal), 'Farmed Type Nut Val retrieved successfully');
    }

    public function by_ft_id($id)
    {
        $farmedTypeNutVal = FarmedTypeNutVal::where('farmed_type_id', $id)->first();

        if (empty($farmedTypeNutVal)) {
            return $this->sendError('Farmed Type Nutritional Values not found');
        }

        return $this->sendResponse(new FarmedTypeNutValResource($farmedTypeNutVal), 'Farmed Type Nutritional Values retrieved successfully');
    }

    /**
     * Update the specified FarmedTypeNutVal in storage.
     * PUT/PATCH /farmedTypeNutVals/{id}
     *
     * @param int $id
     * @param UpdateFarmedTypeNutValAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeNutValAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeNutVal $farmedTypeNutVal */
        $farmedTypeNutVal = $this->farmedTypeNutValRepository->find($id);

        if (empty($farmedTypeNutVal)) {
            return $this->sendError('Farmed Type Nut Val not found');
        }

        $farmedTypeNutVal = $this->farmedTypeNutValRepository->update($input, $id);

        return $this->sendResponse(new FarmedTypeNutValResource($farmedTypeNutVal), 'FarmedTypeNutVal updated successfully');
    }

    /**
     * Remove the specified FarmedTypeNutVal from storage.
     * DELETE /farmedTypeNutVals/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FarmedTypeNutVal $farmedTypeNutVal */
        $farmedTypeNutVal = $this->farmedTypeNutValRepository->find($id);

        if (empty($farmedTypeNutVal)) {
            return $this->sendError('Farmed Type Nut Val not found');
        }

        $farmedTypeNutVal->delete();

        return $this->sendSuccess('Farmed Type Nut Val deleted successfully');
    }
}
