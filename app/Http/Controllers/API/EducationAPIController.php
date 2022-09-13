<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEducationAPIRequest;
use App\Http\Requests\API\UpdateEducationAPIRequest;
use App\Models\Education;
use App\Repositories\EducationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\EducationResource;
use Response;

/**
 * Class EducationController
 * @package App\Http\Controllers\API
 */

class EducationAPIController extends AppBaseController
{
    /** @var  EducationRepository */
    private $educationRepository;

    public function __construct(EducationRepository $educationRepo)
    {
        $this->educationRepository = $educationRepo;
    }

    /**
     * Display a listing of the Education.
     * GET|HEAD /education
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $education = $this->educationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(EducationResource::collection($education), 'Education retrieved successfully');
    }

    /**
     * Store a newly created Education in storage.
     * POST /education
     *
     * @param CreateEducationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEducationAPIRequest $request)
    {
        $input = $request->all();

        $education = $this->educationRepository->create($input);

        return $this->sendResponse(new EducationResource($education), 'Education saved successfully');
    }

    /**
     * Display the specified Education.
     * GET|HEAD /education/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Education $education */
        $education = $this->educationRepository->find($id);

        if (empty($education)) {
            return $this->sendError('Education not found');
        }

        return $this->sendResponse(new EducationResource($education), 'Education retrieved successfully');
    }

    /**
     * Update the specified Education in storage.
     * PUT/PATCH /education/{id}
     *
     * @param int $id
     * @param UpdateEducationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEducationAPIRequest $request)
    {
        $input = $request->all();

        /** @var Education $education */
        $education = $this->educationRepository->find($id);

        if (empty($education)) {
            return $this->sendError('Education not found');
        }

        $education = $this->educationRepository->update($input, $id);

        return $this->sendResponse(new EducationResource($education), 'Education updated successfully');
    }

    /**
     * Remove the specified Education from storage.
     * DELETE /education/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Education $education */
        $education = $this->educationRepository->find($id);

        if (empty($education)) {
            return $this->sendError('Education not found');
        }

        $education->delete();

        return $this->sendSuccess('Education deleted successfully');
    }
}
