<?php

namespace App\Http\Controllers\API;

use App\Http\Helpers\Compatibility;
use App\Http\Requests\API\CreateFarmAPIRequest;
use App\Repositories\FarmRepository;
use App\Repositories\UserRepository;
use App\Repositories\SaltDetailRepository;
use App\Repositories\ChemicalDetailRepository;
use App\Repositories\LocationRepository;
use App\Repositories\PostRepository;

use App\Repositories\SaltTypeRepository;
use App\Repositories\HomePlantPotSizeRepository;
use App\Repositories\AcidityTypeRepository;
use App\Repositories\FarmActivityTypeRepository;
use App\Repositories\FarmedTypeClassRepository;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\MeasuringUnitRepository;
use App\Repositories\IrrigationWayRepository;
use App\Repositories\HomePlantIlluminatingSourceRepository;
use App\Repositories\FarmingWayRepository;
use App\Repositories\AnimalBreedingPurposeRepository;
use App\Repositories\FarmingMethodRepository;
use App\Repositories\SeedlingSourceRepository;
use App\Repositories\ChemicalFertilizerSourceRepository;
use App\Repositories\AnimalFodderTypeRepository;
use App\Repositories\AnimalFodderSourceRepository;
use App\Repositories\AnimalMedicineSourceRepository;
use App\Repositories\SoilTypeRepository;

use App\Http\Resources\AcidityTypeResource;
use App\Http\Resources\SaltTypeResource;
use App\Http\Resources\HomePlantPotSizeResource;
use App\Http\Resources\FarmActivityTypeResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\MeasuringUnitResource;
use App\Http\Resources\IrrigationWayResource;
use App\Http\Resources\HomePlantIlluminatingSourceResource;
use App\Http\Resources\FarmingWayResource;
use App\Http\Resources\AnimalBreedingPurposeResource;
use App\Http\Resources\FarmingMethodResource;
use App\Http\Resources\AnimalFodderTypeResource;
use App\Http\Resources\SoilTypeResource;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmResource;
use Illuminate\Http\Request;

use App\Http\Helpers\WeatherApi;
use App\Http\Requests\API\RateFarmRequest;
use App\Http\Resources\BusinessXsResource;
use App\Http\Resources\FarmAdminResource;
use App\Http\Resources\FarmCollection;
use App\Http\Resources\FarmedTypeXsWithChildrenResource;
use App\Http\Resources\FarmSmResource;
use App\Http\Resources\FarmWithReportsResource;
use App\Http\Resources\FarmXsResource;
use App\Models\Business;
use App\Models\Farm;
use App\Models\FarmedType;
use Illuminate\Support\Facades\DB;

class FarmAPIController extends AppBaseController
{

    private $farmRepository;
    private $saltDetailRepository;
    private $chemicalDetailRepository;

    private $acidityTypeRepository;
    private $saltTypeRepository;
    private $homePlantPotSizeRepository;
    private $farmActivityTypeRepository;
    private $farmedTypeRepository;
    private $measuringUnitRepository;
    private $irrigationWayRepository;
    private $homePlantIlluminatingSourceRepository;
    private $farmingWayRepository;
    private $animalBreedingPurposeRepository;
    private $farmingMethodRepository;
    private $animalFodderTypeRepository;
    private $soilTypeRepository;

    private $locationRepository;

    public function __construct(
        FarmRepository $farmRepo,
        UserRepository $userRepo,
        SaltDetailRepository $saltDetailRepo,
        ChemicalDetailRepository $chemicalDetailRepo,
        PostRepository $postRepo,

        AcidityTypeRepository $acidityTypeRepo,
        SaltTypeRepository $saltTypeRepo,
        HomePlantPotSizeRepository $homePlantPotSizeRepo,
        FarmActivityTypeRepository $farmActivityTypeRepo,
        FarmedTypeClassRepository $farmedTypeClassRepo,
        FarmedTypeRepository $farmedTypeRepo,
        MeasuringUnitRepository $measuringUnitRepo,
        IrrigationWayRepository $irrigationWayRepo,
        HomePlantIlluminatingSourceRepository $homePlantIlluminatingSourceRepo,
        FarmingWayRepository $farmingWayRepo,
        AnimalBreedingPurposeRepository $animalBreedingPurposeRepo,
        FarmingMethodRepository $farmingMethodRepo,
        AnimalFodderTypeRepository $animalFodderTypeRepo,
        SoilTypeRepository $soilTypeRepo,
        LocationRepository $locationRepo
    )
    {
        $this->farmRepository = $farmRepo;
        $this->userRepository = $userRepo;
        $this->saltDetailRepository = $saltDetailRepo;
        $this->chemicalDetailRepository = $chemicalDetailRepo;
        $this->postRepository = $postRepo;

        $this->acidityTypeRepository = $acidityTypeRepo;
        $this->saltTypeRepository = $saltTypeRepo;
        $this->homePlantPotSizeRepository = $homePlantPotSizeRepo;
        $this->farmActivityTypeRepository = $farmActivityTypeRepo;
        $this->farmedTypeClassRepository = $farmedTypeClassRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->measuringUnitRepository = $measuringUnitRepo;
        $this->irrigationWayRepository = $irrigationWayRepo;
        $this->homePlantIlluminatingSourceRepository = $homePlantIlluminatingSourceRepo;
        $this->farmingWayRepository = $farmingWayRepo;
        $this->animalBreedingPurposeRepository = $animalBreedingPurposeRepo;
        $this->farmingMethodRepository = $farmingMethodRepo;
        $this->animalFodderTypeRepository = $animalFodderTypeRepo;
        $this->soilTypeRepository = $soilTypeRepo;

        $this->locationRepository = $locationRepo;

        $this->middleware('permission:farms.index')->only(['index']);
        $this->middleware('permission:farms.destroy')->only(['destroy']);
    }


    // WEATHER

    public function get_weather(Request $request)
    {
        $resp = WeatherApi::instance()->weather_api($request);
        return $resp['status'] ?
        $this->sendResponse($resp['data'] , 'Weather data retrieved successfully')
        :
        $this->sendError('Error fetching the weather data');
    }

    public function search($query)
    {
        $query = \Str::lower(trim($query));
        // with('business') -> for canBeSeen inside the resource
        $farms = Farm::with('business')->where(function($q) use($query){
            $q->whereRaw('LOWER(`code`) regexp ? ', ".*$query.*")
              ->orWhereHas('farmed_type', function($qq) use($query){
                $qq->whereRaw('LOWER(`name`) regexp ? ', '"(ar|en)":"\w*' . $query . '.*"');
            });
        })->get();
        $farms = collect(FarmXsResource::collection($farms))->where('canBeSeen', true)->values();
        return $this->sendResponse(['count' => $farms->count(), 'all' => $farms], 'Farms retrieved successfully');
    }


    // admin
    public function index(Request $request)
    {
        try{
            $farms = $this->farmRepository->all(
                $request->except(['page', 'perPage']),
                $request->get('page') ?? 1,
                $request->get('perPage')
            );

            return $this->sendResponse(['all' => FarmCollection::collection($farms['all']), 'meta' => $farms['meta']], 'Farms retrieved successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    // admin
    public function admin_show($id)
    {
        try{
            /** @var Farm $farm */
            $farm = $this->farmRepository->find($id);

            if (empty($farm)) {
                return $this->sendError('Farm not found');
            }

            return $this->sendResponse(new FarmAdminResource($farm), 'Farm retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    // admin
    public function ladybug_rate_farm(RateFarmRequest $request)
    {
        try
        {
            $farm = $this->farmRepository->find($request->farm);
            $farm->ladybug_rating = $request->rating;
            $farm->save();
            return $this->sendSuccess("You have rated farm with $request->rating stars successfully");
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function relations_index()
    {
        try{
            $data['acidity_types'] = AcidityTypeResource::collection($this->acidityTypeRepository->all());
            $data['salt_types'] = SaltTypeResource::collection($this->saltTypeRepository->all());
            $data['farm_activity_types'] = FarmActivityTypeResource::collection($this->farmActivityTypeRepository->all());
            $data['home_plant_pot_sizes'] = HomePlantPotSizeResource::collection($this->homePlantPotSizeRepository->all());

            $farmedTypes = FarmedType::global()->with('children')->get();
            $data['crops_types'] = FarmedTypeXsWithChildrenResource::collection($farmedTypes->where('farm_activity_type_id', 1));
            $data['trees_types'] = FarmedTypeXsWithChildrenResource::collection($farmedTypes->where('farm_activity_type_id', 2));
            $data['homeplants_types'] = FarmedTypeXsWithChildrenResource::collection($farmedTypes->where('farm_activity_type_id', 3));
            $data['animals_types'] = FarmedTypeXsWithChildrenResource::collection($farmedTypes->where('farm_activity_type_id', 4));

            $data['area_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'area']));
            $data['acidity_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'acidity']));
            $data['salt_concentration_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'salt_concentration']));
            $data['irrigation_ways'] = IrrigationWayResource::collection($this->irrigationWayRepository->all());
            $data['home_plant_illuminating_sources'] = HomePlantIlluminatingSourceResource::collection($this->homePlantIlluminatingSourceRepository->all());
            $data['farming_ways'] = FarmingWayResource::collection($this->farmingWayRepository->where(['type' => 'farming']));
            $data['breeding_ways'] = FarmingWayResource::collection($this->farmingWayRepository->where(['type' => 'breeding']));
            $data['animal_breeding_purposes'] = AnimalBreedingPurposeResource::collection($this->animalBreedingPurposeRepository->all());
            $data['farming_methods'] = FarmingMethodResource::collection($this->farmingMethodRepository->all());
            $data['animal_fodder_types'] = AnimalFodderTypeResource::collection($this->animalFodderTypeRepository->all());
            $data['soil_types'] = SoilTypeResource::collection($this->soilTypeRepository->all());

            $data['seedling_sources'] = BusinessXsResource::collection(Business::farm()->get(['id', 'com_name', 'business_field_id']));
            $data['chemical_fertilizer_sources'] = BusinessXsResource::collection(Business::fertilizer()->get(['id', 'com_name', 'business_field_id']));
            $data['animal_fodder_sources'] = BusinessXsResource::collection(Business::fodder()->get(['id', 'com_name', 'business_field_id']));
            $data['animal_medicine_sources'] = BusinessXsResource::collection(Business::vetmed()->get(['id', 'com_name', 'business_field_id']));

            return $this->sendResponse(['all' => $data], 'Farms relations retrieved successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function toggleArchive($id)
    {
        try
        {
            $farm = $this->farmRepository->find($id);

            if (empty($farm))
            {
                return $this->sendError('Farm not found');
            }

            if($farm->archived)
            {
                $this->farmRepository->save_localized(['archived' => false], $id);
                return $this->sendSuccess('Farm unarchived successfully');
            }
            else
            {
                $this->farmRepository->save_localized(['archived' => true], $id);
                return $this->sendSuccess('Farm archived successfully');
            }
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function getArchived()
    {
        try
        {
            $archived_farms = auth()->user()->farms()->where('archived', true)->get();
            return $this->sendResponse(['all' => FarmResource::collection($archived_farms)], 'Archived farms retrieved successfully');
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function store(CreateFarmAPIRequest $request)
    {
        try{
            $business = Business::find($request->business_id);
            if(!auth()->user()->hasPermission("create-activity", $business))
                return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

            DB::beginTransaction();

            $input = $request->validated();

            $fat_id = $input["farm_activity_type_id"];

            //all 1,2,3,4
            $farm_detail['business_id'] = $input['business_id'];
            $farm_detail['admin_id'] = auth()->id();
            $farm_detail['real'] = $input["real"];
            $farm_detail['archived'] = $input["archived"];
            $farm_detail['farm_activity_type_id'] = $input["farm_activity_type_id"];
            $farm_detail['farmed_type_id'] = $input["farmed_type_id"];
            $farm_detail['farmed_type_class_id'] = $input["farmed_type_class_id"] ?? null;
            $farm_detail['farming_date'] = $input["farming_date"];
            $farm_detail['code'] = $input['code'];
            if(isset($input['location']) && !empty($input['location'])){
                $location = $input['location'];
                $saved_location = $this->locationRepository->create($location);
                $farm_detail['location_id'] = $saved_location->id;
            }

            //crops 1
            if($fat_id == 1)
            {
                $farm_detail['farming_method_id'] = $input["farming_method_id"];
            }

            //crops, animals 1,4
            if($fat_id == 1 || $fat_id == 4)
            {
                $farm_detail['farming_way_id'] = $input["farming_way_id"];
            }


            //crops, trees 1,2
            if($fat_id == 1 || $fat_id == 2)
            {
                $farm_detail['area'] = $input["area"];
                $farm_detail['area_unit_id'] = $input["area_unit_id"];

                $farm_detail['irrigation_way_id'] = $input["irrigation_way_id"];
                $farm_detail['soil_type_id'] = $input["soil_type_id"];

                if(isset($input['soil']) && !empty($input['soil'])){
                    if(isset($input['soil']['salt']) && !empty($input['soil']['salt'])){
                        //soil.salt
                        $soil_salt_detail = $input["soil"]["salt"];
                        $soil_salt_detail["saltable_type"] = "soil";
                        $saved_soil_salt_detail = $this->saltDetailRepository->create($soil_salt_detail);
                        //soil
                        $soil_detail['salt_detail_id'] = $saved_soil_salt_detail->id;
                        unset($input["soil"]["salt"]);
                    }
                    $soil_detail = $input["soil"];
                    $soil_detail['type'] = "soil";

                    $saved_soil_detail = $this->chemicalDetailRepository->create($soil_detail);
                    $farm_detail['soil_detail_id'] = $saved_soil_detail->id;
                }

                if(isset($input['irrigation']) && !empty($input['irrigation'])){
                    if(isset($input['irrigation']['salt']) && !empty($input['irrigation']['salt'])){
                        //irrigation.salt
                        $irrigation_salt_detail = $input["irrigation"]["salt"];
                        $irrigation_salt_detail["saltable_type"] = "irrigation";
                        $saved_irrigation_salt_detail = $this->saltDetailRepository->create($irrigation_salt_detail);
                        //irrigation
                        $irrigation_detail['salt_detail_id'] = $saved_irrigation_salt_detail->id;
                        unset($input["irrigation"]["salt"]);
                    }
                    $irrigation_detail = $input["irrigation"];
                    $irrigation_detail['type'] = "irrigation";
                    $saved_irrigation_detail = $this->chemicalDetailRepository->create($irrigation_detail);
                    $farm_detail['irrigation_water_detail_id'] = $saved_irrigation_detail->id;
                }
            }

            //homeplant, trees, animals 2,3,4
            if($fat_id == 2 || $fat_id == 3 || $fat_id == 4)
            {
                $farm_detail['farmed_number'] = $input["farmed_number"];
            }

            //homeplants 3
            if($fat_id == 3)
            {
                $farm_detail['home_plant_pot_size_id'] = $input["home_plant_pot_size_id"];
                $farm_detail['home_plant_illuminating_source_id'] = $input["home_plant_illuminating_source_id"];
            }

            // animal 4
            if($fat_id == 4)
            {
                $farm_detail['animal_breeding_purpose_id'] = $input["animal_breeding_purpose_id"];
                if(isset($input['drink']) && !empty($input['drink'])){
                    if(isset($input['drink']['salt']) && !empty($input['drink']['salt'])){
                        //drink.salt
                        $drink_salt_detail = $input["drink"]["salt"];
                        $drink_salt_detail["saltable_type"] = "drink";
                        $saved_drink_salt_detail = $this->saltDetailRepository->create($drink_salt_detail);
                        $farm_detail['animal_drink_water_salt_detail_id'] = $saved_drink_salt_detail->id;
                    }
                }
            }

            $farm = $this->farmRepository->create($farm_detail);

            //crops, trees, homeplants 1,2,3
            if($fat_id == 1 || $fat_id == 2 || $fat_id == 3)
            {
                $farm->chemical_fertilizer_sources()->sync($input["chemical_fertilizer_sources"]);
                $farm->seedling_sources()->sync($input["seedling_sources"]);
            }
            // animal 4
            if($fat_id == 4)
            {
                $farm->animal_medicine_sources()->sync($input["animal_medicine_sources"]);
                $farm->animal_fodder_sources()->sync($input["animal_fodder_sources"]);
                $farm->animal_fodder_types()->sync($input["animal_fodder_types"]);
            }
            DB::commit();
            return $this->sendResponse(new FarmResource($farm), 'Farm saved successfully');

        }catch(\Throwable $th){
            DB::rollBack();
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function calculate_compatibility($id)
    {
        $data = (new Compatibility())->calculate_compatibility($id);
        return response()->json($data);
    }


    public function show($id)
    {
        try{
            /** @var Farm $farm */
            $farm = $this->farmRepository->find($id);

            if (empty($farm)) {
                return $this->sendError('Farm not found');
            }

            return $this->sendResponse(new FarmResource($farm), 'Farm retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function farm_with_reports($id)
    {
        try{
            $farm = $this->farmRepository->find($id);
            if (empty($farm))
                return $this->sendError('Farm not found');
            return $this->sendResponse(new FarmWithReportsResource($farm), 'Farm with reports retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function update($id, CreateFarmAPIRequest $request)
    {
        try{
            DB::beginTransaction();
            //update the farm
            $input = $request->validated();

            $farm = $this->farmRepository->find($id);

            if (empty($farm))
                return $this->sendError('Farm not found');

            $business = Business::find($farm->business_id);
            if(!auth()->user()->hasPermission("edit-activity", $business))
                return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

            $fat_id = $input["farm_activity_type_id"];

            //all 1,2,3,4
            $farm_detail['business_id'] = $input['business_id'];
            $farm_detail['real'] = $input["real"];
            $farm_detail['archived'] = $input["archived"];
            $farm_detail['farm_activity_type_id'] = $input["farm_activity_type_id"];
            $farm_detail['farmed_type_id'] = $input["farmed_type_id"];
            $farm_detail['farmed_type_class_id'] = $input["farmed_type_class_id"] ?? null;
            $farm_detail['farming_date'] = $input["farming_date"];
            $farm_detail['code'] = $input['code'];

            if(isset($input['location']) && !empty($input['location'])){
                $location = $input['location'];
                if($farm->location_id)
                    $saved_location = $this->locationRepository->update($location, $farm->location_id);
                else
                    $saved_location = $this->locationRepository->create($location);
                $farm_detail['location_id'] = $saved_location->id;
            }

            //crops 1
            if($fat_id == 1)
            {
                $farm_detail['farming_method_id'] = $input["farming_method_id"];
            }

            //crops, animals 1,4
            if($fat_id == 1 || $fat_id == 4)
            {
                $farm_detail['farming_way_id'] = $input["farming_way_id"];
            }


            //crops, trees 1,2
            if($fat_id == 1 || $fat_id == 2)
            {
                $farm_detail['area'] = $input["area"];
                $farm_detail['area_unit_id'] = $input["area_unit_id"];

                $farm_detail['irrigation_way_id'] = $input["irrigation_way_id"];
                $farm_detail['soil_type_id'] = $input["soil_type_id"];

                if(isset($input['soil']) && !empty($input['soil'])){
                    if(isset($input['soil']['salt']) && !empty($input['soil']['salt'])){
                        //soil.salt
                        $soil_salt_detail = $input["soil"]["salt"];
                        $soil_salt_detail["saltable_type"] = "soil";
                        if($farm->soil_detail->salt_detail_id)
                            $saved_soil_salt_detail = $this->saltDetailRepository->update($soil_salt_detail, $farm->soil_detail->salt_detail_id);
                        else
                            $saved_soil_salt_detail = $this->saltDetailRepository->create($soil_salt_detail);

                        //soil
                        $soil_detail['salt_detail_id'] = $saved_soil_salt_detail->id;
                        unset($input["soil"]["salt"]);
                    }
                    $soil_detail = $input["soil"];
                    $soil_detail['type'] = "soil";
                    if($farm->soil_detail_id)
                        $saved_soil_detail = $this->chemicalDetailRepository->update($soil_detail, $farm->soil_detail_id);
                    else
                        $saved_soil_detail = $this->chemicalDetailRepository->create($soil_detail);
                    $farm_detail['soil_detail_id'] = $saved_soil_detail->id;
                }


                if(isset($input['irrigation']) && !empty($input['irrigation'])){
                    if(isset($input['irrigation']['salt']) && !empty($input['irrigation']['salt'])){
                        //irrigation.salt
                        $irrigation_salt_detail = $input["irrigation"]["salt"];
                        $irrigation_salt_detail["saltable_type"] = "irrigation";
                        if($farm->irrigation_water_detail->salt_detail_id)
                            $saved_irrigation_salt_detail = $this->saltDetailRepository->update($irrigation_salt_detail, $farm->irrigation_water_detail->salt_detail_id);
                        else
                            $saved_irrigation_salt_detail = $this->saltDetailRepository->create($irrigation_salt_detail);

                        //irrigation
                        $irrigation_detail['salt_detail_id'] = $saved_irrigation_salt_detail->id;
                        unset($input["irrigation"]["salt"]);
                    }
                    $irrigation_detail = $input["irrigation"];
                    $irrigation_detail['type'] = "irrigation";
                    if($farm->irrigation_water_detail_id)
                        $saved_irrigation_detail = $this->chemicalDetailRepository->update($irrigation_detail, $farm->irrigation_water_detail_id);
                    else
                        $saved_irrigation_detail = $this->chemicalDetailRepository->create($irrigation_detail);
                    $farm_detail['irrigation_water_detail_id'] = $saved_irrigation_detail->id;
                }

            }

            //homeplant, trees, animals 2,3,4
            if($fat_id == 2 || $fat_id == 3 || $fat_id == 4)
            {
                $farm_detail['farmed_number'] = $input["farmed_number"];
            }

            //homeplants 3
            if($fat_id == 3)
            {
                $farm_detail['home_plant_pot_size_id'] = $input["home_plant_pot_size_id"];
                $farm_detail['home_plant_illuminating_source_id'] = $input["home_plant_illuminating_source_id"];
            }

            // animal 4
            if($fat_id == 4)
            {
                $farm_detail['animal_breeding_purpose_id'] = $input["animal_breeding_purpose_id"];

                if(isset($input['drink']) && !empty($input['drink'])){
                    if(isset($input['drink']['salt']) && !empty($input['drink']['salt'])){
                        //drink.salt
                        $drink_salt_detail = $input["drink"]["salt"];
                        $drink_salt_detail["saltable_type"] = "drink";
                        if($farm->animal_drink_water_salt_detail_id)
                            $saved_drink_salt_detail = $this->saltDetailRepository->update($drink_salt_detail, $farm->animal_drink_water_salt_detail_id);
                        else
                            $saved_drink_salt_detail = $this->saltDetailRepository->create($drink_salt_detail);
                        $farm_detail['animal_drink_water_salt_detail_id'] = $saved_drink_salt_detail->id;
                    }
                }

            }

            $farm = $this->farmRepository->update($farm_detail, $id);

            //crops, trees, homeplants 1,2,3
            if($fat_id == 1 || $fat_id == 2 || $fat_id == 3)
            {
                $farm->chemical_fertilizer_sources()->sync($input["chemical_fertilizer_sources"]);
                $farm->seedling_sources()->sync($input["seedling_sources"]);
            }
            // animal 4
            if($fat_id == 4)
            {
                $farm->animal_medicine_sources()->sync($input["animal_medicine_sources"]);
                $farm->animal_fodder_sources()->sync($input["animal_fodder_sources"]);
                $farm->animal_fodder_types()->sync($input["animal_fodder_types"]);
            }
            DB::commit();
            return $this->sendResponse(new FarmResource($farm), 'Farm updated successfully');
        }catch(\Throwable $th){
            DB::rollBack();
            return $this->sendError($th->getMessage(), 500);
        }

    }


    public function destroy($id)
    {
        try
        {
        /** @var Farm $farm */
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {
            return $this->sendError('Farm not found');
        }
        DB::beginTransaction();
        $farm->location()->delete();
        if($farm->soil_detail){
            $farm->soil_detail->salt_detail()->delete();
            $farm->soil_detail->delete();
        }
        if($farm->irrigation_water_detail){
            $farm->irrigation_water_detail->salt_detail()->delete();
            $farm->irrigation_water_detail->delete();
        }
        $farm->animal_drink_water_salt_detail()->delete();
        foreach($farm->farm_reports as $report){
            $report->tasks()->delete();
            $report->delete();
        }
        $farm->delete();
        DB::commit();
        return $this->sendSuccess('Model deleted successfully');
        }
        catch(\Throwable $th)
        {
            DB::rollBack();
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }

}
