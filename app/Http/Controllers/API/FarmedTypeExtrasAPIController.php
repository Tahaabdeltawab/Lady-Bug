<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeExtrasAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeExtrasAPIRequest;
use App\Models\FarmedTypeExtras;
use App\Repositories\FarmedTypeExtrasRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeExtrasResource;
use App\Models\FarmedType;
use App\Models\IrrigationRate;
use Response;

/**
 * Class FarmedTypeExtrasController
 * @package App\Http\Controllers\API
 */

class FarmedTypeExtrasAPIController extends AppBaseController
{
    /** @var  FarmedTypeExtrasRepository */
    private $farmedTypeExtrasRepository;

    public function __construct(FarmedTypeExtrasRepository $farmedTypeExtrasRepo)
    {
        $this->farmedTypeExtrasRepository = $farmedTypeExtrasRepo;
    }

    /**
     * Display a listing of the FarmedTypeExtras.
     * GET|HEAD /farmedTypeExtras
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FarmedTypeExtrasResource::collection($farmedTypeExtras), 'Farmed Type Extras retrieved successfully');
    }

    public function seedling_types($value = null)
    {
        $types = [
            ['value' => 'seeds', 'name' => app()->getLocale() == 'ar' ?  'بذور' : 'Seeds'],
            ['value' => 'seedlings', 'name' => app()->getLocale() == 'ar' ?  'شتلات' : 'Seedlings'],
        ];

        if($value){
            return collect($types)->firstWhere('value', $value);
        }else
            return $types;
    }
    public function getRelations()
    {
        $data['irrigation_rates'] = IrrigationRate::all();
        $data['seedling_types'] = $this->seedling_types();
        return $this->sendResponse($data, 'relations retrieved');
    }
    /**
     * Store a newly created FarmedTypeExtras in storage.
     * POST /farmedTypeExtras
     *
     * @param CreateFarmedTypeExtrasAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeExtrasAPIRequest $request)
    {
        $input = $request->validated();
        $farmedTypeExtras = FarmedTypeExtras::updateOrCreate(['farmed_type_id' => $request->farmed_type_id], $input);

        return $this->sendResponse(new FarmedTypeExtrasResource($farmedTypeExtras), 'Farmed Type Extras saved successfully');
    }

    /**
     * Display the specified FarmedTypeExtras.
     * GET|HEAD /farmedTypeExtras/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FarmedTypeExtras $farmedTypeExtras */
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->find($id);

        if (empty($farmedTypeExtras)) {
            return $this->sendError('Farmed Type Extras not found');
        }

        return $this->sendResponse(new FarmedTypeExtrasResource($farmedTypeExtras), 'Farmed Type Extras retrieved successfully');
    }

    public function by_ft_id($id)
    {
        $farmedTypeExtras = FarmedTypeExtras::where('farmed_type_id', $id)->first();

        if (empty($farmedTypeExtras)) {
            return $this->sendError('Farmed Type Extras not found');
        }

        return $this->sendResponse(new FarmedTypeExtrasResource($farmedTypeExtras), 'Farmed Type Extras retrieved successfully');
    }

    /**
     * Update the specified FarmedTypeExtras in storage.
     * PUT/PATCH /farmedTypeExtras/{id}
     *
     * @param int $id
     * @param UpdateFarmedTypeExtrasAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeExtrasAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeExtras $farmedTypeExtras */
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->find($id);

        if (empty($farmedTypeExtras)) {
            return $this->sendError('Farmed Type Extras not found');
        }

        $farmedTypeExtras = $this->farmedTypeExtrasRepository->update($input, $id);

        return $this->sendResponse(new FarmedTypeExtrasResource($farmedTypeExtras), 'FarmedTypeExtras updated successfully');
    }

    /**
     * Remove the specified FarmedTypeExtras from storage.
     * DELETE /farmedTypeExtras/{id}
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
        /** @var FarmedTypeExtras $farmedTypeExtras */
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->find($id);

        if (empty($farmedTypeExtras)) {
            return $this->sendError('Farmed Type Extras not found');
        }

        $farmedTypeExtras->delete();

        return $this->sendSuccess('Farmed Type Extras deleted successfully');
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
