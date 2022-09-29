<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmReportAPIRequest;
use App\Http\Requests\API\UpdateFarmReportAPIRequest;
use App\Models\FarmReport;
use App\Repositories\FarmReportRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmReportResource;
use App\Models\Setting;
use App\Models\Transaction;
use Response;

/**
 * Class FarmReportController
 * @package App\Http\Controllers\API
 */

class FarmReportAPIController extends AppBaseController
{
    /** @var  FarmReportRepository */
    private $farmReportRepository;

    public function __construct(FarmReportRepository $farmReportRepo)
    {
        $this->farmReportRepository = $farmReportRepo;
    }

    /**
     * Display a listing of the FarmReport.
     * GET|HEAD /farmReports
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $farmReports = $this->farmReportRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FarmReportResource::collection($farmReports), 'Farm Reports retrieved successfully');
    }

    /**
     * Store a newly created FarmReport in storage.
     * POST /farmReports
     *
     * @param CreateFarmReportAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmReportAPIRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = auth()->id();
        $farmReport = $this->farmReportRepository->create($input);

        $create_report_price = Setting::where('name', 'report_price')->value('value');
        Transaction::create([
            'type' => 'out',
            'user_id' => auth()->id(),
            'gateway' => '',
            'total' => $create_report_price,
            'description' => 'Create report'
        ]);
        auth()->user()->balance -= $create_report_price;
        auth()->user()->save();

        return $this->sendResponse(new FarmReportResource($farmReport), 'Farm Report saved successfully');
    }

    /**
     * Display the specified FarmReport.
     * GET|HEAD /farmReports/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FarmReport $farmReport */
        $farmReport = $this->farmReportRepository->find($id);

        if (empty($farmReport)) {
            return $this->sendError('Farm Report not found');
        }

        return $this->sendResponse(new FarmReportResource($farmReport), 'Farm Report retrieved successfully');
    }

    /**
     * Update the specified FarmReport in storage.
     * PUT/PATCH /farmReports/{id}
     *
     * @param int $id
     * @param UpdateFarmReportAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmReportAPIRequest $request)
    {
        $input = $request->all();

        /** @var FarmReport $farmReport */
        $farmReport = $this->farmReportRepository->find($id);

        if (empty($farmReport)) {
            return $this->sendError('Farm Report not found');
        }

        $farmReport = $this->farmReportRepository->update($input, $id);

        return $this->sendResponse(new FarmReportResource($farmReport), 'FarmReport updated successfully');
    }

    /**
     * Remove the specified FarmReport from storage.
     * DELETE /farmReports/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FarmReport $farmReport */
        $farmReport = $this->farmReportRepository->find($id);

        if (empty($farmReport)) {
            return $this->sendError('Farm Report not found');
        }

        $farmReport->delete();

        return $this->sendSuccess('Farm Report deleted successfully');
    }
}
