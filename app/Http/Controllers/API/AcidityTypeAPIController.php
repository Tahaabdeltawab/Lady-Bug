<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAcidityTypeAPIRequest;
use App\Http\Requests\API\UpdateAcidityTypeAPIRequest;
use App\Models\AcidityType;
use App\Repositories\AcidityTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AcidityTypeResource;
use Response;

/**
 * Class AcidityTypeController
 * @package App\Http\Controllers\API
 */

class AcidityTypeAPIController extends AppBaseController
{
    /** @var  AcidityTypeRepository */
    private $acidityTypeRepository;

    public function __construct(AcidityTypeRepository $acidityTypeRepo)
    {
        $this->acidityTypeRepository = $acidityTypeRepo;

        $this->middleware('permission:acidity_types.store')->only(['store']);
        $this->middleware('permission:acidity_types.update')->only(['update']);
        $this->middleware('permission:acidity_types.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $acidityTypes = $this->acidityTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AcidityTypeResource::collection($acidityTypes), 'Acidity Types retrieved successfully');
    }

    public function store(CreateAcidityTypeAPIRequest $request)
    {
        $input = $request->all();

        $acidityType = $this->acidityTypeRepository->save_localized($input);

        return $this->sendResponse(new AcidityTypeResource($acidityType), 'Acidity Type saved successfully');
    }

    public function show($id)
    {
        /** @var AcidityType $acidityType */
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            return $this->sendError('Acidity Type not found');
        }

        return $this->sendResponse(new AcidityTypeResource($acidityType), 'Acidity Type retrieved successfully');
    }

    public function update($id, CreateAcidityTypeAPIRequest $request)
    {
        $input = $request->all();

        /** @var AcidityType $acidityType */
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            return $this->sendError('Acidity Type not found');
        }

        $acidityType = $this->acidityTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new AcidityTypeResource($acidityType), 'AcidityType updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var AcidityType $acidityType */
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            return $this->sendError('Acidity Type not found');
        }

        $acidityType->delete();

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
