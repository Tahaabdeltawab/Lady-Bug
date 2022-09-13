<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCareerAPIRequest;
use App\Http\Requests\API\UpdateCareerAPIRequest;
use App\Models\Career;
use App\Repositories\CareerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CareerResource;
use Response;

/**
 * Class CareerController
 * @package App\Http\Controllers\API
 */

class CareerAPIController extends AppBaseController
{
    /** @var  CareerRepository */
    private $careerRepository;

    public function __construct(CareerRepository $careerRepo)
    {
        $this->careerRepository = $careerRepo;
    }

    /**
     * Display a listing of the Career.
     * GET|HEAD /careers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $careers = $this->careerRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CareerResource::collection($careers), 'Careers retrieved successfully');
    }

    /**
     * Store a newly created Career in storage.
     * POST /careers
     *
     * @param CreateCareerAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCareerAPIRequest $request)
    {
        $input = $request->all();

        $career = $this->careerRepository->create($input);

        return $this->sendResponse(new CareerResource($career), 'Career saved successfully');
    }

    /**
     * Display the specified Career.
     * GET|HEAD /careers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Career $career */
        $career = $this->careerRepository->find($id);

        if (empty($career)) {
            return $this->sendError('Career not found');
        }

        return $this->sendResponse(new CareerResource($career), 'Career retrieved successfully');
    }

    /**
     * Update the specified Career in storage.
     * PUT/PATCH /careers/{id}
     *
     * @param int $id
     * @param UpdateCareerAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCareerAPIRequest $request)
    {
        $input = $request->all();

        /** @var Career $career */
        $career = $this->careerRepository->find($id);

        if (empty($career)) {
            return $this->sendError('Career not found');
        }

        $career = $this->careerRepository->update($input, $id);

        return $this->sendResponse(new CareerResource($career), 'Career updated successfully');
    }

    /**
     * Remove the specified Career from storage.
     * DELETE /careers/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Career $career */
        $career = $this->careerRepository->find($id);

        if (empty($career)) {
            return $this->sendError('Career not found');
        }

        $career->delete();

        return $this->sendSuccess('Career deleted successfully');
    }
}
