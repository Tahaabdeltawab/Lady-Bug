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
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PathogenTypeResource::collection($pathogenTypes), 'Pathogen Types retrieved successfully');
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
        $input = $request->all();

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
        $input = $request->all();

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
        /** @var PathogenType $pathogenType */
        $pathogenType = $this->pathogenTypeRepository->find($id);

        if (empty($pathogenType)) {
            return $this->sendError('Pathogen Type not found');
        }

        $pathogenType->delete();

        return $this->sendSuccess('Pathogen Type deleted successfully');
    }
}
