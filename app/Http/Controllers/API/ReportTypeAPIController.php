<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateReportTypeAPIRequest;
use App\Http\Requests\API\UpdateReportTypeAPIRequest;
use App\Models\ReportType;
use App\Repositories\ReportTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ReportTypeResource;
use Response;

/**
 * Class ReportTypeController
 * @package App\Http\Controllers\API
 */

class ReportTypeAPIController extends AppBaseController
{
    /** @var  ReportTypeRepository */
    private $reportTypeRepository;

    public function __construct(ReportTypeRepository $reportTypeRepo)
    {
        $this->reportTypeRepository = $reportTypeRepo;

        $this->middleware('permission:report_types.store')->only(['store']);
        $this->middleware('permission:report_types.update')->only(['update']);
        $this->middleware('permission:report_types.destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the ReportType.
     * GET|HEAD /reportTypes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $reportTypes = $this->reportTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ReportTypeResource::collection($reportTypes), 'Report Types retrieved successfully');
    }

    /**
     * Store a newly created ReportType in storage.
     * POST /reportTypes
     *
     * @param CreateReportTypeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateReportTypeAPIRequest $request)
    {
        $input = $request->validated();

        $reportType = $this->reportTypeRepository->save_localized($input);

        return $this->sendResponse(new ReportTypeResource($reportType), 'Report Type saved successfully');
    }

    /**
     * Display the specified ReportType.
     * GET|HEAD /reportTypes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ReportType $reportType */
        $reportType = $this->reportTypeRepository->find($id);

        if (empty($reportType)) {
            return $this->sendError('Report Type not found');
        }

        return $this->sendResponse(new ReportTypeResource($reportType), 'Report Type retrieved successfully');
    }

    /**
     * Update the specified ReportType in storage.
     * PUT/PATCH /reportTypes/{id}
     *
     * @param int $id
     * @param UpdateReportTypeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, CreateReportTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ReportType $reportType */
        $reportType = $this->reportTypeRepository->find($id);

        if (empty($reportType)) {
            return $this->sendError('Report Type not found');
        }

        $reportType = $this->reportTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new ReportTypeResource($reportType), 'ReportType updated successfully');
    }

    /**
     * Remove the specified ReportType from storage.
     * DELETE /reportTypes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            /** @var ReportType $reportType */
            $reportType = $this->reportTypeRepository->find($id);

            if (empty($reportType)) {
                return $this->sendError('Report Type not found');
            }

            $reportType->delete();

            return $this->sendSuccess('Report Type deleted successfully');
        }
        catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Database\QueryException)
                return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
                return $this->sendError('Error deleting the model');
        }
    }
}
