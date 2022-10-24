<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePathogenAPIRequest;
use App\Http\Requests\API\UpdatePathogenAPIRequest;
use App\Models\Pathogen;
use App\Repositories\PathogenRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PathogenResource;
use Response;

/**
 * Class PathogenController
 * @package App\Http\Controllers\API
 */

class PathogenAPIController extends AppBaseController
{
    /** @var  PathogenRepository */
    private $pathogenRepository;

    public function __construct(PathogenRepository $pathogenRepo)
    {
        $this->pathogenRepository = $pathogenRepo;
    }

    /**
     * Display a listing of the Pathogen.
     * GET|HEAD /pathogens
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $pathogens = $this->pathogenRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PathogenResource::collection($pathogens), 'Pathogens retrieved successfully');
    }

    /**
     * Store a newly created Pathogen in storage.
     * POST /pathogens
     *
     * @param CreatePathogenAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePathogenAPIRequest $request)
    {
        $input = $request->validated();

        $pathogen = $this->pathogenRepository->create($input);

        return $this->sendResponse(new PathogenResource($pathogen), 'Pathogen saved successfully');
    }

    /**
     * Display the specified Pathogen.
     * GET|HEAD /pathogens/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Pathogen $pathogen */
        $pathogen = $this->pathogenRepository->find($id);

        if (empty($pathogen)) {
            return $this->sendError('Pathogen not found');
        }

        return $this->sendResponse(new PathogenResource($pathogen), 'Pathogen retrieved successfully');
    }

    /**
     * Update the specified Pathogen in storage.
     * PUT/PATCH /pathogens/{id}
     *
     * @param int $id
     * @param UpdatePathogenAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePathogenAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Pathogen $pathogen */
        $pathogen = $this->pathogenRepository->find($id);

        if (empty($pathogen)) {
            return $this->sendError('Pathogen not found');
        }

        $pathogen = $this->pathogenRepository->update($input, $id);

        return $this->sendResponse(new PathogenResource($pathogen), 'Pathogen updated successfully');
    }

    /**
     * Remove the specified Pathogen from storage.
     * DELETE /pathogens/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Pathogen $pathogen */
        $pathogen = $this->pathogenRepository->find($id);

        if (empty($pathogen)) {
            return $this->sendError('Pathogen not found');
        }

        $pathogen->delete();

        return $this->sendSuccess('Pathogen deleted successfully');
    }
}
