<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSaltTypeAPIRequest;
use App\Http\Requests\API\UpdateSaltTypeAPIRequest;
use App\Models\SaltType;
use App\Repositories\SaltTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SaltTypeResource;
use Response;

/**
 * Class SaltTypeController
 * @package App\Http\Controllers\API
 */

class SaltTypeAPIController extends AppBaseController
{
    /** @var  SaltTypeRepository */
    private $saltTypeRepository;

    public function __construct(SaltTypeRepository $saltTypeRepo)
    {
        $this->saltTypeRepository = $saltTypeRepo;

        $this->middleware('permission:salt_types.store')->only(['store']);
        $this->middleware('permission:salt_types.update')->only(['update']);
        $this->middleware('permission:salt_types.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $saltTypes = $this->saltTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => SaltTypeResource::collection($saltTypes['all']), 'meta' => $saltTypes['meta']], 'Salt Types retrieved successfully');
    }

    public function store(CreateSaltTypeAPIRequest $request)
    {
        $input = $request->validated();

        $saltType = $this->saltTypeRepository->create($input);

        return $this->sendResponse(new SaltTypeResource($saltType), 'Salt Type saved successfully');
    }

    public function show($id)
    {
        /** @var SaltType $saltType */
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            return $this->sendError('Salt Type not found');
        }

        return $this->sendResponse(new SaltTypeResource($saltType), 'Salt Type retrieved successfully');
    }

    public function update($id, CreateSaltTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SaltType $saltType */
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            return $this->sendError('Salt Type not found');
        }

        $saltType = $this->saltTypeRepository->update($input, $id);

        return $this->sendResponse(new SaltTypeResource($saltType), 'SaltType updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var SaltType $saltType */
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            return $this->sendError('Salt Type not found');
        }

        $saltType->delete();

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
