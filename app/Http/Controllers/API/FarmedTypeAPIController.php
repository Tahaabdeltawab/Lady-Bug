<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeAPIRequest;
use App\Models\FarmedType;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\AssetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/**
 * Class FarmedTypeController
 * @package App\Http\Controllers\API
 */

class FarmedTypeAPIController extends AppBaseController
{
    /** @var  FarmedTypeRepository */
    private $farmedTypeRepository;

    public function __construct(FarmedTypeRepository $farmedTypeRepo, AssetRepository $assetRepo)
    {
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->assetRepository = $assetRepo;

        $this->middleware('permission:farmed_types.store')->only(['store']);
        $this->middleware('permission:farmed_types.update')->only(['update']);
        $this->middleware('permission:farmed_types.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $farmedTypes = $this->farmedTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeResource::collection($farmedTypes)], 'Farmed Types retrieved successfully');
    }

    public function search($query)
    {
        $query = strtolower(trim($query));
        $farmedTypes = FarmedType::whereRaw('LOWER(`name`) regexp ? ', '"(ar|en)":"\w*' . $query . '.*"')->get();

        return $this->sendResponse(['all' => FarmedTypeResource::collection($farmedTypes)], 'Farmed Types retrieved successfully');
    }

    public function store(CreateFarmedTypeAPIRequest $request)
    {
        $to_save['name_ar_localized'] = $request->name_ar_localized;
        $to_save['name_en_localized'] = $request->name_en_localized;
        $to_save['parent_id'] = $request->parent_id;
        $to_save['farm_activity_type_id'] = $request->farm_activity_type_id;

        $to_save['flowering_time'] = $request->flowering_time;
        $to_save['maturity_time'] = $request->maturity_time;

        $to_save['farming_temperature'] = is_array($request->farming_temperature) ? json_encode($request->farming_temperature) : $request->farming_temperature;
        $to_save['flowering_temperature'] = is_array($request->flowering_temperature) ? json_encode($request->flowering_temperature) : $request->flowering_temperature;
        $to_save['maturity_temperature'] = is_array($request->maturity_temperature) ? json_encode($request->maturity_temperature) : $request->maturity_temperature;
        $to_save['humidity'] = is_array($request->humidity) ? json_encode($request->humidity) : $request->humidity;
        $to_save['suitable_soil_salts_concentration'] = is_array($request->suitable_soil_salts_concentration) ? json_encode($request->suitable_soil_salts_concentration) : $request->suitable_soil_salts_concentration;
        $to_save['suitable_water_salts_concentration'] = is_array($request->suitable_water_salts_concentration) ? json_encode($request->suitable_water_salts_concentration) : $request->suitable_water_salts_concentration;
        $to_save['suitable_ph'] = is_array($request->suitable_ph) ? json_encode($request->suitable_ph) : $request->suitable_ph;
        $to_save['suitable_soil_types'] = is_array($request->suitable_soil_types) ? json_encode($request->suitable_soil_types) : $request->suitable_soil_types;

        $farmedType = $this->farmedTypeRepository->save_localized($to_save);

        if($photo = $request->file('photo'))
        {
            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo, 'farmed-type');
            $farmedType->asset()->create($oneasset);

        }


        return $this->sendResponse(new FarmedTypeResource($farmedType), 'Farmed Type saved successfully');
    }

    public function show($id)
    {
        /** @var FarmedType $farmedType */
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            return $this->sendError('Farmed Type not found');
        }

        return $this->sendResponse(new FarmedTypeResource($farmedType), 'Farmed Type retrieved successfully');
    }

    public function update($id, CreateFarmedTypeAPIRequest $request)
    {

        /** @var FarmedType $farmedType */
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            return $this->sendError('Farmed Type not found');
        }

        $validator = Validator::make($request->all(), [

        ]);

        if($validator->fails())
        {
            return $this->sendError(json_encode($validator->errors()), 5050);
        }

        $to_save['name_ar_localized'] = $request->name_ar_localized;
        $to_save['name_en_localized'] = $request->name_en_localized;
        $to_save['parent_id'] = $request->parent_id;
        $to_save['farm_activity_type_id'] = $request->farm_activity_type_id;

        $to_save['flowering_time'] = $request->flowering_time;
        $to_save['maturity_time'] = $request->maturity_time;

        $to_save['farming_temperature'] = is_array($request->farming_temperature) ? json_encode($request->farming_temperature) : $request->farming_temperature;
        $to_save['flowering_temperature'] = is_array($request->flowering_temperature) ? json_encode($request->flowering_temperature) : $request->flowering_temperature;
        $to_save['maturity_temperature'] = is_array($request->maturity_temperature) ? json_encode($request->maturity_temperature) : $request->maturity_temperature;
        $to_save['humidity'] = is_array($request->humidity) ? json_encode($request->humidity) : $request->humidity;
        $to_save['suitable_soil_salts_concentration'] = is_array($request->suitable_soil_salts_concentration) ? json_encode($request->suitable_soil_salts_concentration) : $request->suitable_soil_salts_concentration;
        $to_save['suitable_water_salts_concentration'] = is_array($request->suitable_water_salts_concentration) ? json_encode($request->suitable_water_salts_concentration) : $request->suitable_water_salts_concentration;
        $to_save['suitable_ph'] = is_array($request->suitable_ph) ? json_encode($request->suitable_ph) : $request->suitable_ph;
        $to_save['suitable_soil_types'] = is_array($request->suitable_soil_types) ? json_encode($request->suitable_soil_types) : $request->suitable_soil_types;

        $farmedType = $this->farmedTypeRepository->save_localized($to_save, $id);

        if($photo = $request->file('photo'))
        {
            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo, 'farmed-type');
            $farmedType->asset()->create($oneasset);

        }

        return $this->sendResponse(new FarmedTypeResource($farmedType), 'Farmed Type updated successfully');

        $farmedType = $this->farmedTypeRepository->save_localized($input, $id);
    }

    public function destroy($id)
    {
        try
        {
        /** @var FarmedType $farmedType */
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            return $this->sendError('Farmed Type not found');
        }

        $farmedType->delete();
        $farmedType->asset->delete();

        return $this->sendSuccess('Farmed Type deleted successfully');
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
