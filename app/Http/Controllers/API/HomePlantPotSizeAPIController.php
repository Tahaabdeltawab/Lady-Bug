<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHomePlantPotSizeAPIRequest;
use App\Http\Requests\API\UpdateHomePlantPotSizeAPIRequest;
use App\Models\HomePlantPotSize;
use App\Repositories\HomePlantPotSizeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\HomePlantPotSizeResource;
use Response;

/**
 * Class HomePlantPotSizeController
 * @package App\Http\Controllers\API
 */

class HomePlantPotSizeAPIController extends AppBaseController
{
    /** @var  HomePlantPotSizeRepository */
    private $homePlantPotSizeRepository;

    public function __construct(HomePlantPotSizeRepository $homePlantPotSizeRepo)
    {
        $this->homePlantPotSizeRepository = $homePlantPotSizeRepo;

        $this->middleware('permission:home_plant_pot_sizes.store')->only(['store']);
        $this->middleware('permission:home_plant_pot_sizes.update')->only(['update']);
        $this->middleware('permission:home_plant_pot_sizes.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $homePlantPotSizes = $this->homePlantPotSizeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(HomePlantPotSizeResource::collection($homePlantPotSizes), 'Home Plant Pot Sizes retrieved successfully');
    }

    public function store(CreateHomePlantPotSizeAPIRequest $request)
    {
        $input = $request->all();

        $homePlantPotSize = $this->homePlantPotSizeRepository->create($input);

        return $this->sendResponse(new HomePlantPotSizeResource($homePlantPotSize), 'Home Plant Pot Size saved successfully');
    }

    public function show($id)
    {
        /** @var HomePlantPotSize $homePlantPotSize */
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            return $this->sendError('Home Plant Pot Size not found');
        }

        return $this->sendResponse(new HomePlantPotSizeResource($homePlantPotSize), 'Home Plant Pot Size retrieved successfully');
    }

    public function update($id, UpdateHomePlantPotSizeAPIRequest $request)
    {
        $input = $request->all();

        /** @var HomePlantPotSize $homePlantPotSize */
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            return $this->sendError('Home Plant Pot Size not found');
        }

        $homePlantPotSize = $this->homePlantPotSizeRepository->update($input, $id);

        return $this->sendResponse(new HomePlantPotSizeResource($homePlantPotSize), 'HomePlantPotSize updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var HomePlantPotSize $homePlantPotSize */
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            return $this->sendError('Home Plant Pot Size not found');
        }

        $homePlantPotSize->delete();

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
