<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeClassAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeClassAPIRequest;
use App\Models\FarmedTypeClass;
use App\Repositories\FarmedTypeClassRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeClassResource;
use Response;

/**
 * Class FarmedTypeClassController
 * @package App\Http\Controllers\API
 */

class FarmedTypeClassAPIController extends AppBaseController
{
    /** @var  FarmedTypeClassRepository */
    private $farmedTypeClassRepository;

    public function __construct(FarmedTypeClassRepository $farmedTypeClassRepo)
    {
        $this->farmedTypeClassRepository = $farmedTypeClassRepo;

        $this->middleware('permission:farmed_type_classes.store')->only(['store']);
        $this->middleware('permission:farmed_type_classes.update')->only(['update']);
        $this->middleware('permission:farmed_type_classes.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $farmedTypeClasses = $this->farmedTypeClassRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeClassResource::collection($farmedTypeClasses)], 'Farmed Type Classes retrieved successfully');
    }

    public function store(CreateFarmedTypeClassAPIRequest $request)
    {
        $input = $request->validated();

        $farmedTypeClass = $this->farmedTypeClassRepository->create($input);

        return $this->sendResponse(new FarmedTypeClassResource($farmedTypeClass), 'Farmed Type Class saved successfully');
    }

    public function show($id)
    {
        /** @var FarmedTypeClass $farmedTypeClass */
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            return $this->sendError('Farmed Type Class not found');
        }

        return $this->sendResponse(new FarmedTypeClassResource($farmedTypeClass), 'Farmed Type Class retrieved successfully');
    }

    public function update($id, CreateFarmedTypeClassAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeClass $farmedTypeClass */
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            return $this->sendError('Farmed Type Class not found');
        }

        $farmedTypeClass = $this->farmedTypeClassRepository->update($input, $id);

        return $this->sendResponse(new FarmedTypeClassResource($farmedTypeClass), 'FarmedTypeClass updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var FarmedTypeClass $farmedTypeClass */
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            return $this->sendError('Farmed Type Class not found');
        }

        $farmedTypeClass->delete();

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
