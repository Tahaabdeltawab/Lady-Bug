<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSaltDetailAPIRequest;
use App\Http\Requests\API\UpdateSaltDetailAPIRequest;
use App\Models\SaltDetail;
use App\Repositories\SaltDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SaltDetailResource;
use Response;

/**
 * Class SaltDetailController
 * @package App\Http\Controllers\API
 */

class SaltDetailAPIController extends AppBaseController
{
    /** @var  SaltDetailRepository */
    private $saltDetailRepository;

    public function __construct(SaltDetailRepository $saltDetailRepo)
    {
        $this->saltDetailRepository = $saltDetailRepo;
    }

    public function index(Request $request)
    {
        $saltDetails = $this->saltDetailRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => SaltDetailResource::collection($saltDetails['all']), 'meta' => $saltDetails['meta']], 'Salt Details retrieved successfully');
    }

    public function store(CreateSaltDetailAPIRequest $request)
    {
        $input = $request->validated();

        $saltDetail = $this->saltDetailRepository->create($input);

        return $this->sendResponse(new SaltDetailResource($saltDetail), 'Salt Detail saved successfully');
    }

    public function show($id)
    {
        /** @var SaltDetail $saltDetail */
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            return $this->sendError('Salt Detail not found');
        }

        return $this->sendResponse(new SaltDetailResource($saltDetail), 'Salt Detail retrieved successfully');
    }

    public function update($id, CreateSaltDetailAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SaltDetail $saltDetail */
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            return $this->sendError('Salt Detail not found');
        }

        $saltDetail = $this->saltDetailRepository->update($input, $id);

        return $this->sendResponse(new SaltDetailResource($saltDetail), 'SaltDetail updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var SaltDetail $saltDetail */
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            return $this->sendError('Salt Detail not found');
        }

        $saltDetail->delete();

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
