<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFertilizerAPIRequest;
use App\Http\Requests\API\UpdateFertilizerAPIRequest;
use App\Models\Fertilizer;
use App\Repositories\FertilizerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CountryResource;
use App\Http\Resources\FertilizerResource;
use App\Models\Country;
use App\Models\NutElemValue;
use Illuminate\Support\Facades\DB;
use Response;

/**
 * Class FertilizerController
 * @package App\Http\Controllers\API
 */

class FertilizerAPIController extends AppBaseController
{
    /** @var  FertilizerRepository */
    private $fertilizerRepository;

    public function __construct(FertilizerRepository $fertilizerRepo)
    {
        $this->fertilizerRepository = $fertilizerRepo;

        $this->middleware('permission:fertilizers.index')->only(['admin_index']);
        $this->middleware('permission:fertilizers.show')->only(['admin_show']);
        $this->middleware('permission:fertilizers.store')->only(['store']);
        $this->middleware('permission:fertilizers.update')->only(['update']);
        $this->middleware('permission:fertilizers.destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the Fertilizer.
     * GET|HEAD /fertilizers
     *
     * @param Request $request
     * @return Response
     */
    public function admin_index(Request $request)
    {
        return $this->index($request);
    }

    public function admin_show($id)
    {
        return $this->show($id);
    }

    public function index(Request $request)
    {
        $fertilizers = $this->fertilizerRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => FertilizerResource::collection($fertilizers['all']), 'meta' => $fertilizers['meta']], 'Fertilizers retrieved successfully');
    }


    public function getRelations()
    {
        return $this->sendResponse([
            'countries' => CountryResource::collection(Country::all()),
            'dosage_forms' => [
                ['value' => 'liquid', 'name' => app()->getLocale()=='ar' ?  'سائل' : 'liquid'],
                ['value' => 'powder', 'name' => app()->getLocale()=='ar' ?  'بودرة' : 'powder'],
            ]
        ], 'fertilizer relations retrieved successfully');
    }


    /**
     * Store a newly created Fertilizer in storage.
     * POST /fertilizers
     *
     * @param CreateFertilizerAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFertilizerAPIRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->validated();

            $nev = NutElemValue::create($input['nut_elem_value']);
            $input['nut_elem_value_id'] = $nev->id;
            $fertilizer = Fertilizer::create($input);

            if($assets = $request->file('assets'))
            {
                foreach($assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'fertilizer');
                    $fertilizer->assets()->create($oneasset);
                }
            }

            DB::commit();
            return $this->sendResponse(new FertilizerResource($fertilizer), 'Fertilizer saved successfully');
        } catch(\Throwable $th){
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Display the specified Fertilizer.
     * GET|HEAD /fertilizers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Fertilizer $fertilizer */
        $fertilizer = $this->fertilizerRepository->find($id);

        if (empty($fertilizer)) {
            return $this->sendError('Fertilizer not found');
        }

        return $this->sendResponse(new FertilizerResource($fertilizer), 'Fertilizer retrieved successfully');
    }

    /**
     * Update the specified Fertilizer in storage.
     * PUT/PATCH /fertilizers/{id}
     *
     * @param int $id
     * @param UpdateFertilizerAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFertilizerAPIRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->validated();

            /** @var Fertilizer $fertilizer */
            $fertilizer = $this->fertilizerRepository->find($id);

            if (empty($fertilizer)) {
                return $this->sendError('Fertilizer not found');
            }

            $fertilizer = $this->fertilizerRepository->update($input, $id);
            $fertilizer->nutElemValue()->update($input['nut_elem_value']);

            if($assets = $request->file('assets'))
            {
                foreach ($fertilizer->assets as $ass) {
                    $ass->delete();
                }
                foreach($assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'fertilizer');
                    $fertilizer->assets()->create($oneasset);
                }
            }

            DB::commit();
            return $this->sendResponse(new FertilizerResource($fertilizer), 'Fertilizer updated successfully');
        } catch(\Throwable $th){
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Remove the specified Fertilizer from storage.
     * DELETE /fertilizers/{id}
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
        /** @var Fertilizer $fertilizer */
        $fertilizer = $this->fertilizerRepository->find($id);

        if (empty($fertilizer)) {
            return $this->sendError('Fertilizer not found');
        }

        $fertilizer->delete();
        $fertilizer->nutElemValue()->delete();

        return $this->sendSuccess('Fertilizer deleted successfully');
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
