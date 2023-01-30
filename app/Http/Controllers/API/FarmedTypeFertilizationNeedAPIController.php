<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeFertilizationNeedAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeFertilizationNeedAPIRequest;
use App\Models\FarmedTypeFertilizationNeed;
use App\Repositories\FarmedTypeFertilizationNeedRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeFertilizationNeedResource;
use App\Models\FarmedTypeStage;
use App\Models\NutElemValue;
use Response;

/**
 * Class FarmedTypeFertilizationNeedController
 * @package App\Http\Controllers\API
 */

class FarmedTypeFertilizationNeedAPIController extends AppBaseController
{
    /** @var  FarmedTypeFertilizationNeedRepository */
    private $farmedTypeFertilizationNeedRepository;

    public function __construct(FarmedTypeFertilizationNeedRepository $farmedTypeFertilizationNeedRepo)
    {
        $this->farmedTypeFertilizationNeedRepository = $farmedTypeFertilizationNeedRepo;

        $this->middleware('permission:farmed_types.update')->only(['store']);
    }

    /**
     * Display a listing of the FarmedTypeFertilizationNeed.
     * GET|HEAD /farmedTypeFertilizationNeeds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeFertilizationNeeds = $this->farmedTypeFertilizationNeedRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => FarmedTypeFertilizationNeedResource::collection($farmedTypeFertilizationNeeds['all']), 'meta' => $farmedTypeFertilizationNeeds['meta']], 'Farmed Type Fertilization Needs retrieved successfully');
    }

    // public function pers($value = null)
    // {
    //     $pers = [
    //         ['value' => 'acre', 'name' => app()->getLocale() == 'ar' ?  'فدان' : 'Acre'],
    //         ['value' => 'tree', 'name' => app()->getLocale() == 'ar' ?  'شجرة' : 'Tree'],
    //     ];

    // if($value !== null){
    // return collect($pers)->firstWhere('value', $value);
    //     }else
    //         return $pers;
    // }
    public function getRelations()
    {
        $data['farmed_type_stages'] = FarmedTypeStage::all();
        // $data['pers'] = $this->pers();
        return $this->sendResponse($data, 'relations retrieved');
    }

    /**
     * Store a newly created FarmedTypeFertilizationNeed in storage.
     * POST /farmedTypeFertilizationNeeds
     *
     * @param CreateFarmedTypeFertilizationNeedAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeFertilizationNeedAPIRequest $request)
    {
        $input = $request->validated();
        $farmedTypeFertilizationNeed = FarmedTypeFertilizationNeed::where('farmed_type_id', $request->farmed_type_id)->where('farmed_type_stage_id', $request->farmed_type_stage_id)->first();
        unset($input['nut_elem_value']);
        if($farmedTypeFertilizationNeed){
            NutElemValue::where('id', $farmedTypeFertilizationNeed->nut_elem_value_id)->update($request->nut_elem_value);
            $farmedTypeFertilizationNeed->update($input);
        }else{
            $nutElemValue = NutElemValue::create($request->nut_elem_value);
            $input['nut_elem_value_id'] = $nutElemValue->id;
            $farmedTypeFertilizationNeed = FarmedTypeFertilizationNeed::create($input);
        }
        // $farmedTypeFertilizationNeed = FarmedTypeFertilizationNeed::updateOrCreate(['farmed_type_id' => $request->farmed_type_id], $input);

        return $this->sendResponse(new FarmedTypeFertilizationNeedResource($farmedTypeFertilizationNeed), 'Farmed Type Fertilization Need saved successfully');
    }

    /**
     * Display the specified FarmedTypeFertilizationNeed.
     * GET|HEAD /farmedTypeFertilizationNeeds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FarmedTypeFertilizationNeed $farmedTypeFertilizationNeed */
        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->find($id);

        if (empty($farmedTypeFertilizationNeed)) {
            return $this->sendError('Farmed Type Fertilization Need not found');
        }

        return $this->sendResponse(new FarmedTypeFertilizationNeedResource($farmedTypeFertilizationNeed), 'Farmed Type Fertilization Need retrieved successfully');
    }

    public function by_ft_id($id, $fts = null)
    {
        $farmedTypeFertilizationNeed = FarmedTypeFertilizationNeed::where('farmed_type_id', $id)
        ->when($fts, function($q) use($fts){
            return $q->where('farmed_type_stage_id', $fts);
        });
        if($fts){
            $farmedTypeFertilizationNeed = $farmedTypeFertilizationNeed->first();
            $method = 'make';
        }else{
            $farmedTypeFertilizationNeed = $farmedTypeFertilizationNeed->get();
            $method = 'collection';
        }

        if (empty($farmedTypeFertilizationNeed)) {
            return $this->sendError('farmed Type Fertilization Need not found');
        }

        return $this->sendResponse(FarmedTypeFertilizationNeedResource::$method($farmedTypeFertilizationNeed), 'farmed Type Fertilization Need retrieved successfully');
    }

    /**
     * Update the specified FarmedTypeFertilizationNeed in storage.
     * PUT/PATCH /farmedTypeFertilizationNeeds/{id}
     *
     * @param int $id
     * @param UpdateFarmedTypeFertilizationNeedAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeFertilizationNeedAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeFertilizationNeed $farmedTypeFertilizationNeed */
        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->find($id);

        if (empty($farmedTypeFertilizationNeed)) {
            return $this->sendError('Farmed Type Fertilization Need not found');
        }

        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->update($input, $id);

        return $this->sendResponse(new FarmedTypeFertilizationNeedResource($farmedTypeFertilizationNeed), 'FarmedTypeFertilizationNeed updated successfully');
    }

    /**
     * Remove the specified FarmedTypeFertilizationNeed from storage.
     * DELETE /farmedTypeFertilizationNeeds/{id}
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
        /** @var FarmedTypeFertilizationNeed $farmedTypeFertilizationNeed */
        $farmedTypeFertilizationNeed = $this->farmedTypeFertilizationNeedRepository->find($id);

        if (empty($farmedTypeFertilizationNeed)) {
            return $this->sendError('Farmed Type Fertilization Need not found');
        }

        $farmedTypeFertilizationNeed->delete();
        $farmedTypeFertilizationNeed->nutElemValue()->delete();

        return $this->sendSuccess('Farmed Type Fertilization Need deleted successfully');
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
