<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeAPIRequest;
use App\Models\FarmedType;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\AssetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ResistantDiseaseRequest;
use App\Http\Requests\API\SensitiveDiseaseRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\FarmedTypeAdminResource;
use App\Http\Resources\FarmedTypeAdminSmResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\NamesCountriesResource;
use App\Http\Resources\SensitiveDiseaseResource;
use App\Models\Country;
use App\Models\FarmActivityType;
use App\Models\SensitiveDiseaseFarmedType;
use App\Models\SoilType;
use Illuminate\Support\Facades\DB;
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

    // admin
    public function admin_show($id)
    {
        /** @var FarmedType $farmedType */
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            return $this->sendError('Farmed Type not found');
        }

        return $this->sendResponse(new FarmedTypeAdminResource($farmedType), 'Farmed Type retrieved successfully');
    }

    public function admin_index(Request $request)
    {
        $farmedTypes = $this->farmedTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => FarmedTypeAdminSmResource::collection($farmedTypes['all']), 'meta' => $farmedTypes['meta']], 'Farmed Types retrieved successfully');
    }


    public function index(Request $request)
    {
        $farmedTypes = $this->farmedTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => FarmedTypeResource::collection($farmedTypes['all']), 'meta' => $farmedTypes['meta']], 'Farmed Types retrieved successfully');
    }

    public function getRelations()
    {
        $data['farm_activity_types'] = FarmActivityType::all();
        $data['parents'] = FarmedType::parentScope()->get(['id', 'name']);
        $data['countries'] = Country::all();
        $data['soil_types'] = SoilType::all();

        return $this->sendResponse($data, 'relations retrieved');
    }

    public function search($query)
    {
        $query = \Str::lower(trim($query));
        $farmedTypes = FarmedType::whereRaw('LOWER(`name`) regexp ? ', '"(ar|en)":"\w*' . $query . '.*"')->get();

        return $this->sendResponse(['all' => FarmedTypeResource::collection($farmedTypes)], 'Farmed Types retrieved successfully');
    }

    public function get_popular_countries($id)
    {
        $farmedType = FarmedType::find($id);
        if(!$farmedType) return $this->sendError('Farmed type not found');
        return $this->sendResponse(CountryResource::collection($farmedType->popular_countries), 'popular countries retrieved successfully');
    }

    public function get_names_countries($id)
    {
        $farmedType = FarmedType::find($id);
        if(!$farmedType) return $this->sendError('Farmed type not found');
        $names = $farmedType->names_countries()->get();
        return $this->sendResponse(NamesCountriesResource::collection($names), 'different countries names of farmed type retrieved successfully');
    }

    public function popular_countries(Request $request)
    {
        $farmedType = FarmedType::find($request->farmed_type_id);
        if(!$farmedType) return $this->sendError('Farmed type not found');

        DB::table('country_farmed_type')->where('farmed_type_id', $farmedType->id)
            ->whereNotNull('common_name')
            ->update(['popular' => 0]);
        DB::table('country_farmed_type')->where('farmed_type_id', $farmedType->id)
            ->whereNull('common_name')
            ->delete();

        $cnames = $farmedType->names_countries()->pluck('country_id');
        foreach($request->countries as $c_id){
            if($cnames->contains($c_id))
                DB::table('country_farmed_type')->where('farmed_type_id', $farmedType->id)->where('country_id', $c_id)
                    ->update(['popular' => 1]);
            else
                $farmedType->popular_countries()->attach($c_id, ['popular' => 1]);
        }
        return $this->sendSuccess('saved');
    }

    public function names_countries(Request $request)
    {
        $farmedType = FarmedType::find($request->farmed_type_id);
        if(!$farmedType) return $this->sendError('Farmed type not found');

        DB::table('country_farmed_type')->where('farmed_type_id', $farmedType->id)
            ->where('popular', 1)
            ->update(['common_name' => null]);
        DB::table('country_farmed_type')->where('farmed_type_id', $farmedType->id)
            ->where('popular', 0)
            ->delete();

        $populars = $farmedType->popular_countries()->pluck('country_id');
        foreach($request->countries as $c){
            if($populars->contains($c['id']))
                DB::table('country_farmed_type')->where('farmed_type_id', $farmedType->id)->where('country_id', $c['id'])
                    ->update(['common_name' => $c['name']]);
            else
                $farmedType->names_countries()->attach($c['id'], ['common_name' => $c['name']]);
        }
        return $this->sendSuccess('saved');
    }

    public function store(CreateFarmedTypeAPIRequest $request)
    {
        $to_save = $request->validated();
        $to_save['farming_temperature'] = is_array($request->farming_temperature) ? json_encode($request->farming_temperature) : $request->farming_temperature;
        $to_save['flowering_temperature'] = is_array($request->flowering_temperature) ? json_encode($request->flowering_temperature) : $request->flowering_temperature;
        $to_save['maturity_temperature'] = is_array($request->maturity_temperature) ? json_encode($request->maturity_temperature) : $request->maturity_temperature;
        $to_save['humidity'] = is_array($request->humidity) ? json_encode($request->humidity) : $request->humidity;
        $to_save['suitable_soil_salts_concentration'] = is_array($request->suitable_soil_salts_concentration) ? json_encode($request->suitable_soil_salts_concentration) : $request->suitable_soil_salts_concentration;
        $to_save['suitable_water_salts_concentration'] = is_array($request->suitable_water_salts_concentration) ? json_encode($request->suitable_water_salts_concentration) : $request->suitable_water_salts_concentration;
        $to_save['suitable_ph'] = is_array($request->suitable_ph) ? json_encode($request->suitable_ph) : $request->suitable_ph;
        $to_save['suitable_soil_types'] = is_array($request->suitable_soil_types) ? json_encode($request->suitable_soil_types) : $request->suitable_soil_types;

        $farmedType = $this->farmedTypeRepository->create($to_save);

        if($photo = $request->file('photo')){
            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo, 'farmed-type');
            $farmedType->asset()->create($oneasset);
        }else{
            return $this->sendError('Farmed Type should have a photo!');
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

        if (empty($farmedType))
            return $this->sendError('Farmed Type not found');

        $to_save = $request->validated();
        $to_save['farming_temperature'] = is_array($request->farming_temperature) ? json_encode($request->farming_temperature) : $request->farming_temperature;
        $to_save['flowering_temperature'] = is_array($request->flowering_temperature) ? json_encode($request->flowering_temperature) : $request->flowering_temperature;
        $to_save['maturity_temperature'] = is_array($request->maturity_temperature) ? json_encode($request->maturity_temperature) : $request->maturity_temperature;
        $to_save['humidity'] = is_array($request->humidity) ? json_encode($request->humidity) : $request->humidity;
        $to_save['suitable_soil_salts_concentration'] = is_array($request->suitable_soil_salts_concentration) ? json_encode($request->suitable_soil_salts_concentration) : $request->suitable_soil_salts_concentration;
        $to_save['suitable_water_salts_concentration'] = is_array($request->suitable_water_salts_concentration) ? json_encode($request->suitable_water_salts_concentration) : $request->suitable_water_salts_concentration;
        $to_save['suitable_ph'] = is_array($request->suitable_ph) ? json_encode($request->suitable_ph) : $request->suitable_ph;
        $to_save['suitable_soil_types'] = is_array($request->suitable_soil_types) ? json_encode($request->suitable_soil_types) : $request->suitable_soil_types;

        $farmedType = $this->farmedTypeRepository->update($to_save, $id);

        if($photo = $request->file('photo'))
        {
            if($farmedType->asset)
                    $farmedType->asset->delete();
            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo, 'farmed-type');
            $farmedType->asset()->create($oneasset);
            $farmedType->load('asset');
        }
        return $this->sendResponse(new FarmedTypeResource($farmedType), 'Farmed Type updated successfully');
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

            DB::beginTransaction();

            $farmedType->asset->delete();
            $farmedType->extra()->delete();

            foreach($farmedType->fneeds as $fneed){
                $fneed->nutElemValue()->delete();
                $fneed->delete();
            }

            $farmedType->taxonomy()->delete();
            $farmedType->marketing()->delete();
            $farmedType->nutVal()->delete();
            // popular_countries & names countries
            DB::table('country_farmed_type')->where('farmed_type_id', $id)->delete();
            DB::table('favorites')->where('favoriteable_type', 'App\Models\FarmedType')->where('favoriteable_id', $id)->delete();
            $farmedType->resistant_diseases()->detach();
            $sensitives = SensitiveDiseaseFarmedType::where('farmed_type_id', $id)->get();
            foreach ($sensitives as $sen) {
                foreach ($sen->assets as $ass) {
                    $ass->delete();
                }
                $sen->delete();
            }
            $farmedType->products()->detach();

            $farmedType->delete();
            DB::commit();
            return $this->sendSuccess('Farmed Type deleted successfully');
        }catch(\Throwable $th)
        {
            DB::rollBack();
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }
}
