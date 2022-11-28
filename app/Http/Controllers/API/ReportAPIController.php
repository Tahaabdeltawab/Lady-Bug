<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateReportAPIRequest;
use App\Http\Requests\API\UpdateReportAPIRequest;
use App\Models\Report;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ReportResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Class ReportController
 * @package App\Http\Controllers\API
 */

class ReportAPIController extends AppBaseController
{
    /** @var  ReportRepository */
    private $reportRepository;

    public function __construct(ReportRepository $reportRepo)
    {
        $this->reportRepository = $reportRepo;

        $this->middleware('permission:reports.index')->only(['index']);
        $this->middleware('permission:reports.show')->only(['show']);
        $this->middleware('permission:reports.update')->only(['update']);
        $this->middleware('permission:reports.destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the Report.
     * GET|HEAD /reports
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $reports = $this->reportRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => ReportResource::collection($reports['all']), 'meta' => $reports['meta']], 'Reports retrieved successfully');
    }

    /**
     * Store a newly created Report in storage.
     * POST /reports
     *
     * @param CreateReportAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateReportAPIRequest $request)
    {
        try {
            $data['description'] = $request->description;
            $data['report_type_id'] = $request->report_type_id;
            $data['reportable_type'] = 'App\Models\Post';
            $data['reportable_id'] = $request->post_id;
            $data['report_type_id'] = $request->report_type_id;
            $data['reporter_id'] = auth()->id();

            $report = $this->reportRepository->create($data);

            if ($assets = $request->file('assets')) {
                foreach ($assets as $asset) {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'report');
                    $report->assets()->create($oneasset);
                }
            }

            return $this->sendResponse(new ReportResource($report), __('Report saved successfully'));
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * Display the specified Report.
     * GET|HEAD /reports/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Report $report */
        $report = $this->reportRepository->find($id);

        if (empty($report)) {
            return $this->sendError('Report not found');
        }

        return $this->sendResponse(new ReportResource($report), 'Report retrieved successfully');
    }

    /**
     * Update the specified Report in storage.
     * PUT/PATCH /reports/{id}
     *
     * @param int $id
     * @param UpdateReportAPIRequest $request
     *
     * @return Response
     */
    public function update($id, CreateReportAPIRequest $request)
    {
        // $input = $request->validated();

        /** @var Report $report */
        $report = $this->reportRepository->find($id);

        if (empty($report)) {
            return $this->sendError('Report not found');
        }

        try {
            $data['description'] = $request->description;
            $data['report_type_id'] = $request->report_type_id;
            $data['reportable_type'] = 'App\Models\Post';
            $data['reportable_id'] = $request->post_id;
            $data['report_type_id'] = $request->report_type_id;
            $data['reporter_id'] = auth()->id();

            $report = $this->reportRepository->update($data, $id);

            if ($assets = $request->file('assets')) {
                foreach ($assets as $asset) {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'report');
                    $report->assets()->create($oneasset);
                }
            }

            return $this->sendResponse(new ReportResource($report), __('Report saved successfully'));
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Report from storage.
     * DELETE /reports/{id}
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
        /** @var Report $report */
        $report = $this->reportRepository->find($id);

        if (empty($report)) {
            return $this->sendError('Report not found');
        }

        $report->delete();
        foreach($report->assets as $ass){
          $ass->delete();
        }

        return $this->sendSuccess('Report deleted successfully');
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
