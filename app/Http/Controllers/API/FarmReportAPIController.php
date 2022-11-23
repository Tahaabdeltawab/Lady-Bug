<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmReportAPIRequest;
use App\Http\Requests\API\UpdateFarmReportAPIRequest;
use App\Models\FarmReport;
use App\Repositories\FarmReportRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmReportResource;
use App\Models\Business;
use App\Models\Farm;
use App\Models\FarmedTypeStage;
use App\Models\Setting;
use App\Models\Transaction;
use App\Repositories\LocationRepository;
use Illuminate\Support\Facades\DB;
use Response;

/**
 * Class FarmReportController
 * @package App\Http\Controllers\API
 */

class FarmReportAPIController extends AppBaseController
{
    /** @var  FarmReportRepository */
    private $farmReportRepository;

    public function __construct(FarmReportRepository $farmReportRepo, LocationRepository $locationRepo)
    {
        $this->farmReportRepository = $farmReportRepo;
        $this->locationRepository = $locationRepo;
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


    public function getRelations($farm_id)
    {
        $data['create_report_price'] = Setting::where('name', 'report_price')->value('value');
        $data['user_balance'] = auth()->user()->balance;
        $data['farmed_type_stages'] = FarmedTypeStage::all();
        if(!($farm = Farm::find($farm_id))) return $this->sendError('farm not found');
        $data['fertilization_start_date'] = $farm->fertilization_start_date ? date('Y-m-d', strtotime($farm->fertilization_start_date)) : date('Y-m-d');
        $data['fertilization_unit'] = $farm->farmed_type->farm_activity_type_id == 1 ?
        [['value' => 'acre', 'name' => app()->getLocale()=='ar' ?  'لكل فدان' : 'Per Acre']]
        :
        [['value' => 'tree', 'name' => app()->getLocale()=='ar' ?  'لكل شجرة' : 'Per Tree']];

        return $this->sendResponse($data, 'farm report relations retrieved successfully!');
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
        try
        {
            DB::beginTransaction();

            $business = Business::find($request->business_id);
            if(!auth()->user()->hasPermission("create-report", $business))
                return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

            $input = $request->validated();
            $input['user_id'] = auth()->id();
            Farm::where('id', $input['farm_id'])->update(['fertilization_start_date' => $input['fertilization_start_date']]);
            unset($input['fertilization_start_date']);

            $saved_location = $this->locationRepository->create($input["location"]);
            $input['location_id'] = $saved_location->id;

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

            DB::commit();
            return $this->sendResponse(new FarmReportResource($farmReport), 'Farm Report saved successfully');
        }
        catch(\Throwable $th)
        {
            DB::rollBack();
            return $this->sendError($th->getMessage(), 500);
        }
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
        $input = $request->validated();

        /** @var FarmReport $farmReport */
        $farmReport = $this->farmReportRepository->find($id);

        if (empty($farmReport))
            return $this->sendError('Farm Report not found');

        $business = Business::find($farmReport->business_id);
        if(!auth()->user()->hasPermission("edit-report", $business))
            return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

        Farm::where('id', $input['farm_id'])->update(['fertilization_start_date' => $input['fertilization_start_date']]);
        unset($input['fertilization_start_date']);

        $saved_location = $this->locationRepository->update($input["location"], $farmReport->location_id);
        $input['location_id'] = $saved_location->id;
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
        try
        {
        /** @var FarmReport $farmReport */
        $farmReport = $this->farmReportRepository->find($id);

        if (empty($farmReport)) {
            return $this->sendError('Farm Report not found');
        }

        $farmReport->tasks()->delete();
        $farmReport->location()->delete();
        $farmReport->delete();

        return $this->sendSuccess('Farm Report deleted successfully');
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
