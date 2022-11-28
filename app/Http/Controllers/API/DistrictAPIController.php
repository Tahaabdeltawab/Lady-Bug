<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDistrictAPIRequest;
use App\Http\Requests\API\UpdateDistrictAPIRequest;
use App\Models\District;
use App\Repositories\DistrictRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DistrictResource;
use Response;

/**
 * Class DistrictController
 * @package App\Http\Controllers\API
 */

class DistrictAPIController extends AppBaseController
{
    /** @var  DistrictRepository */
    private $districtRepository;

    public function __construct(DistrictRepository $districtRepo)
    {
        $this->districtRepository = $districtRepo;

        $this->middleware('permission:districts.store')->only(['store']);
        $this->middleware('permission:districts.update')->only(['update']);
        $this->middleware('permission:districts.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $districts = $this->districtRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => DistrictResource::collection($districts['all']), 'meta' => $districts['meta']], 'Districts retrieved successfully');
    }

    public function store(CreateDistrictAPIRequest $request)
    {
        $input = $request->validated();

        $district = $this->districtRepository->create($input);

        return $this->sendResponse(new DistrictResource($district), 'District saved successfully');
    }

    public function show($id)
    {
        /** @var District $district */
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            return $this->sendError('District not found');
        }

        return $this->sendResponse(new DistrictResource($district), 'District retrieved successfully');
    }

    public function update($id, CreateDistrictAPIRequest $request)
    {
        $input = $request->validated();

        /** @var District $district */
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            return $this->sendError('District not found');
        }

        $district = $this->districtRepository->update($input, $id);

        return $this->sendResponse(new DistrictResource($district), 'District updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var District $district */
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            return $this->sendError('District not found');
        }

        $district->delete();

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
