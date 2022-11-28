<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePathogenTypeAPIRequest;
use App\Http\Requests\API\UpdatePathogenTypeAPIRequest;
use App\Models\PathogenType;
use App\Repositories\PathogenTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PathogenTypeResource;
use Response;

/**
 * Class PathogenTypeController
 * @package App\Http\Controllers\API
 */

class PathogenTypeAPIController extends AppBaseController
{
    /** @var  PathogenTypeRepository */
    private $pathogenTypeRepository;

    public function __construct(PathogenTypeRepository $pathogenTypeRepo)
    {
        $this->pathogenTypeRepository = $pathogenTypeRepo;
    }

    /**
     * Display a listing of the PathogenType.
     * GET|HEAD /pathogenTypes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $pathogenTypes = $this->pathogenTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => PathogenTypeResource::collection($pathogenTypes['all']), 'meta' => $pathogenTypes['meta']], 'Pathogen Types retrieved successfully');
    }

    /**
     * Store a newly created PathogenType in storage.
     * POST /pathogenTypes
     *
     * @param CreatePathogenTypeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePathogenTypeAPIRequest $request)
    {
        $input = $request->validated();

        $pathogenType = $this->pathogenTypeRepository->create($input);

        return $this->sendResponse(new PathogenTypeResource($pathogenType), 'Pathogen Type saved successfully');
    }

    /**
     * Display the specified PathogenType.
     * GET|HEAD /pathogenTypes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PathogenType $pathogenType */
        $pathogenType = $this->pathogenTypeRepository->find($id);

        if (empty($pathogenType)) {
            return $this->sendError('Pathogen Type not found');
        }

        return $this->sendResponse(new PathogenTypeResource($pathogenType), 'Pathogen Type retrieved successfully');
    }

    /**
     * Update the specified PathogenType in storage.
     * PUT/PATCH /pathogenTypes/{id}
     *
     * @param int $id
     * @param UpdatePathogenTypeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePathogenTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var PathogenType $pathogenType */
        $pathogenType = $this->pathogenTypeRepository->find($id);

        if (empty($pathogenType)) {
            return $this->sendError('Pathogen Type not found');
        }

        $pathogenType = $this->pathogenTypeRepository->update($input, $id);

        return $this->sendResponse(new PathogenTypeResource($pathogenType), 'PathogenType updated successfully');
    }

    /**
     * Remove the specified PathogenType from storage.
     * DELETE /pathogenTypes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
        /** @var PathogenType $pathogenType */
        $pathogenType = $this->pathogenTypeRepository->find($id);

        if (empty($pathogenType)) {
            return $this->sendError('Pathogen Type not found');
        }

        $pathogenType->delete();

        return $this->sendSuccess('Pathogen Type deleted successfully');
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
