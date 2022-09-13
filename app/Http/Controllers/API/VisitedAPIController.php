<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateVisitedAPIRequest;
use App\Http\Requests\API\UpdateVisitedAPIRequest;
use App\Models\Visited;
use App\Repositories\VisitedRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\VisitedResource;
use Response;

/**
 * Class VisitedController
 * @package App\Http\Controllers\API
 */

class VisitedAPIController extends AppBaseController
{
    /** @var  VisitedRepository */
    private $visitedRepository;

    public function __construct(VisitedRepository $visitedRepo)
    {
        $this->visitedRepository = $visitedRepo;
    }

    /**
     * Display a listing of the Visited.
     * GET|HEAD /visiteds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $visiteds = $this->visitedRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(VisitedResource::collection($visiteds), 'Visiteds retrieved successfully');
    }

    /**
     * Store a newly created Visited in storage.
     * POST /visiteds
     *
     * @param CreateVisitedAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateVisitedAPIRequest $request)
    {
        $input = $request->all();

        $visited = $this->visitedRepository->create($input);

        return $this->sendResponse(new VisitedResource($visited), 'Visited saved successfully');
    }

    /**
     * Display the specified Visited.
     * GET|HEAD /visiteds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Visited $visited */
        $visited = $this->visitedRepository->find($id);

        if (empty($visited)) {
            return $this->sendError('Visited not found');
        }

        return $this->sendResponse(new VisitedResource($visited), 'Visited retrieved successfully');
    }

    /**
     * Update the specified Visited in storage.
     * PUT/PATCH /visiteds/{id}
     *
     * @param int $id
     * @param UpdateVisitedAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVisitedAPIRequest $request)
    {
        $input = $request->all();

        /** @var Visited $visited */
        $visited = $this->visitedRepository->find($id);

        if (empty($visited)) {
            return $this->sendError('Visited not found');
        }

        $visited = $this->visitedRepository->update($input, $id);

        return $this->sendResponse(new VisitedResource($visited), 'Visited updated successfully');
    }

    /**
     * Remove the specified Visited from storage.
     * DELETE /visiteds/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Visited $visited */
        $visited = $this->visitedRepository->find($id);

        if (empty($visited)) {
            return $this->sendError('Visited not found');
        }

        $visited->delete();

        return $this->sendSuccess('Visited deleted successfully');
    }
}
