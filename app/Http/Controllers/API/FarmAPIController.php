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
use App\Http\Resources\SeedlingSourceResource;
use App\Http\Resources\ChemicalFertilizerSourceResource;
use App\Http\Resources\AnimalFodderTypeResource;
use App\Http\Resources\AnimalFodderSourceResource;
use App\Http\Resources\AnimalMedicineSourceResource;
use App\Http\Resources\SoilTypeResource;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmResource;
use Illuminate\Http\Request;

use App\Http\Helpers\WeatherApi;
use App\Http\Resources\FarmCollection;
use App\Http\Resources\FarmWithReportsResource;
use App\Models\Business;

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
    private $seedlingSourceRepository;
    private $chemicalFertilizerSourceRepository;
    private $animalFodderTypeRepository;
    private $animalFodderSourceRepository;
    private $animalMedicineSourceRepository;
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
        SeedlingSourceRepository $seedlingSourceRepo,
        ChemicalFertilizerSourceRepository $chemicalFertilizerSourceRepo,
        AnimalFodderTypeRepository $animalFodderTypeRepo,
        AnimalFodderSourceRepository $animalFodderSourceRepo,
        AnimalMedicineSourceRepository $animalMedicineSourceRepo,
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
        $this->seedlingSourceRepository = $seedlingSourceRepo;
        $this->chemicalFertilizerSourceRepository = $chemicalFertilizerSourceRepo;
        $this->animalFodderTypeRepository = $animalFodderTypeRepo;
        $this->animalFodderSourceRepository = $animalFodderSourceRepo;
        $this->animalMedicineSourceRepository = $animalMedicineSourceRepo;
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
        $this->sendError('Error fetching the weather data', $resp['data']['cod'], $resp['data']['message']);
    }


    public function index(Request $request)
    {
        try{
            $farms = $this->farmRepository->all();

            return $this->sendResponse(['all' => FarmCollection::collection($farms)], 'Farms retrieved successfully');
        }catch(\Throwable $th){
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


            $data['crops_types'] = FarmedTypeResource::collection($this->farmedTypeRepository
            ->where(['farm_activity_type_id' => 1])->all());

            $data['trees_types'] = FarmedTypeResource::collection($this->farmedTypeRepository
            ->where(['farm_activity_type_id' => 2])->all());

            $data['homeplants_types'] = FarmedTypeResource::collection($this->farmedTypeRepository
            ->where(['farm_activity_type_id' => 3])->all());

            $data['animals_types'] = FarmedTypeResource::collection($this->farmedTypeRepository
            ->where(['farm_activity_type_id' => 4])->all());


            $data['area_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'area'])->all());
            $data['acidity_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'acidity'])->all());
            $data['salt_concentration_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'salt_concentration'])->all());
            $data['irrigation_ways'] = IrrigationWayResource::collection($this->irrigationWayRepository->all());
            $data['home_plant_illuminating_sources'] = HomePlantIlluminatingSourceResource::collection($this->homePlantIlluminatingSourceRepository->all());
            $data['farming_ways'] = FarmingWayResource::collection($this->farmingWayRepository->where(['type' => 'farming'])->all());
            $data['breeding_ways'] = FarmingWayResource::collection($this->farmingWayRepository->where(['type' => 'breeding'])->all());
            $data['animal_breeding_purposes'] = AnimalBreedingPurposeResource::collection($this->animalBreedingPurposeRepository->all());
            $data['farming_methods'] = FarmingMethodResource::collection($this->farmingMethodRepository->all());
            $data['animal_fodder_types'] = AnimalFodderTypeResource::collection($this->animalFodderTypeRepository->all());
            $data['soil_types'] = SoilTypeResource::collection($this->soilTypeRepository->all());

            $data['seedling_sources'] = Business::farm()->get(['id', 'com_name']);
            $data['chemical_fertilizer_sources'] = Business::fertilizer()->get(['id', 'com_name']);
            $data['animal_fodder_sources'] = Business::fodder()->get(['id', 'com_name']);
            $data['animal_medicine_sources'] = Business::vetmed()->get(['id', 'com_name']);

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
            $archived_farms = auth()->user()->farms()->where('archived', true);
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
            $farm_detail['code'] = $this->generateRandomString();

            $location['latitude'] = $input["location"]["latitude"];
            $location['longitude'] = $input["location"]["longitude"];
            $location['country'] = $input["location"]["country"];
            $location['city'] = $input["location"]["city"];
            $location['district'] = $input["location"]["district"];
            $location['details'] = $input["location"]["details"];
            $saved_location = $this->locationRepository->save_localized($location);
            $farm_detail['location_id'] = $saved_location->id;

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
                //soil.salt
                $soil_salt_detail["saltable_type"] = "soil";
                $soil_salt_detail["PH"] = $input["soil"]["salt"]["PH"];
                $soil_salt_detail["CO3"] = $input["soil"]["salt"]["CO3"];
                $soil_salt_detail["HCO3"] = $input["soil"]["salt"]["HCO3"];
                $soil_salt_detail["Cl"] = $input["soil"]["salt"]["Cl"];
                $soil_salt_detail["SO4"] = $input["soil"]["salt"]["SO4"];
                $soil_salt_detail["Ca"] = $input["soil"]["salt"]["Ca"];
                $soil_salt_detail["Mg"] = $input["soil"]["salt"]["Mg"];
                $soil_salt_detail["K"] = $input["soil"]["salt"]["K"];
                $soil_salt_detail["Na"] = $input["soil"]["salt"]["Na"];
                $soil_salt_detail["Na2CO3"] = $input["soil"]["salt"]["Na2CO3"];
                $saved_soil_salt_detail = $this->saltDetailRepository->save_localized($soil_salt_detail);
                //soil
                $soil_detail['salt_detail_id'] = $saved_soil_salt_detail->id;
                $soil_detail['type'] = "soil";
                $soil_detail['acidity_type_id'] = $input["soil"]["acidity_type_id"];
                $soil_detail['acidity_value'] = $input["soil"]["acidity_value"];
                $soil_detail['acidity_unit_id'] = $input["soil"]["acidity_unit_id"];
                $soil_detail['salt_type_id'] = $input["soil"]["salt_type_id"];
                $soil_detail['salt_concentration_value'] = $input["soil"]["salt_concentration_value"];
                $soil_detail['salt_concentration_unit_id'] = $input["soil"]["salt_concentration_unit_id"] ?? null;
                $saved_soil_detail = $this->chemicalDetailRepository->save_localized($soil_detail);
                $farm_detail['soil_detail_id'] = $saved_soil_detail->id;

                //irrigation.salt
                $irrigation_salt_detail["saltable_type"] = "irrigation";
                $irrigation_salt_detail["PH"] = $input["irrigation"]["salt"]["PH"];
                $irrigation_salt_detail["CO3"] = $input["irrigation"]["salt"]["CO3"];
                $irrigation_salt_detail["HCO3"] = $input["irrigation"]["salt"]["HCO3"];
                $irrigation_salt_detail["Cl"] = $input["irrigation"]["salt"]["Cl"];
                $irrigation_salt_detail["SO4"] = $input["irrigation"]["salt"]["SO4"];
                $irrigation_salt_detail["Ca"] = $input["irrigation"]["salt"]["Ca"];
                $irrigation_salt_detail["Mg"] = $input["irrigation"]["salt"]["Mg"];
                $irrigation_salt_detail["K"] = $input["irrigation"]["salt"]["K"];
                $irrigation_salt_detail["Na"] = $input["irrigation"]["salt"]["Na"];
                $irrigation_salt_detail["Na2CO3"] = $input["irrigation"]["salt"]["Na2CO3"];
                $saved_irrigation_salt_detail = $this->saltDetailRepository->save_localized($irrigation_salt_detail);
                //irrigation
                $irrigation_detail['salt_detail_id'] = $saved_irrigation_salt_detail->id;
                $irrigation_detail['type'] = "irrigation";
                $irrigation_detail['acidity_type_id'] = $input["irrigation"]["acidity_type_id"];
                $irrigation_detail['acidity_value'] = $input["irrigation"]["acidity_value"];
                $irrigation_detail['acidity_unit_id'] = $input["irrigation"]["acidity_unit_id"];
                $irrigation_detail['salt_type_id'] = $input["irrigation"]["salt_type_id"];
                $irrigation_detail['salt_concentration_value'] = $input["irrigation"]["salt_concentration_value"];
                $irrigation_detail['salt_concentration_unit_id'] = $input["irrigation"]["salt_concentration_unit_id"] ?? null;
                $saved_irrigation_detail = $this->chemicalDetailRepository->save_localized($irrigation_detail);
                $farm_detail['irrigation_water_detail_id'] = $saved_irrigation_detail->id;
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
                //drink.salt
                $drink_salt_detail["saltable_type"] = "drink";
                $drink_salt_detail["PH"] = $input["drink"]["salt"]["PH"];
                $drink_salt_detail["CO3"] = $input["drink"]["salt"]["CO3"];
                $drink_salt_detail["HCO3"] = $input["drink"]["salt"]["HCO3"];
                $drink_salt_detail["Cl"] = $input["drink"]["salt"]["Cl"];
                $drink_salt_detail["SO4"] = $input["drink"]["salt"]["SO4"];
                $drink_salt_detail["Ca"] = $input["drink"]["salt"]["Ca"];
                $drink_salt_detail["Mg"] = $input["drink"]["salt"]["Mg"];
                $drink_salt_detail["K"] = $input["drink"]["salt"]["K"];
                $drink_salt_detail["Na"] = $input["drink"]["salt"]["Na"];
                $drink_salt_detail["Na2CO3"] = $input["drink"]["salt"]["Na2CO3"];
                $saved_drink_salt_detail = $this->saltDetailRepository->save_localized($drink_salt_detail);
                $farm_detail['animal_drink_water_salt_detail_id'] = $saved_drink_salt_detail->id;
            }

            $farm = $this->farmRepository->save_localized($farm_detail);

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

            return $this->sendResponse(new FarmResource($farm), 'Farm saved successfully');

        }catch(\Throwable $th){
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
            throw $th;
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
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function update($id, CreateFarmAPIRequest $request)
    {
        try{
            //update the farm
            $input = $request->validated();

            $farm = $this->farmRepository->find($id);

            if (empty($farm)) {
                return $this->sendError('Farm not found');
            }

            $fat_id = $input["farm_activity_type_id"];

            //all 1,2,3,4
            $farm_detail['business_id'] = $input['business_id'];
            $farm_detail['real'] = $input["real"];
            $farm_detail['archived'] = $input["archived"];
            $farm_detail['farm_activity_type_id'] = $input["farm_activity_type_id"];
            $farm_detail['farmed_type_id'] = $input["farmed_type_id"];
            $farm_detail['farmed_type_class_id'] = $input["farmed_type_class_id"] ?? null;
            $farm_detail['farming_date'] = $input["farming_date"];

            $location['latitude'] = $input["location"]["latitude"];
            $location['longitude'] = $input["location"]["longitude"];
            $location['country'] = $input["location"]["country"];
            $location['city'] = $input["location"]["city"];
            $location['district'] = $input["location"]["district"];
            $location['details'] = $input["location"]["details"];
            $saved_location = $this->locationRepository->save_localized($location, $farm->location_id);
            $farm_detail['location_id'] = $saved_location->id;

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
                //soil.salt
                $soil_salt_detail["saltable_type"] = "soil";
                $soil_salt_detail["PH"] = $input["soil"]["salt"]["PH"];
                $soil_salt_detail["CO3"] = $input["soil"]["salt"]["CO3"];
                $soil_salt_detail["HCO3"] = $input["soil"]["salt"]["HCO3"];
                $soil_salt_detail["Cl"] = $input["soil"]["salt"]["Cl"];
                $soil_salt_detail["SO4"] = $input["soil"]["salt"]["SO4"];
                $soil_salt_detail["Ca"] = $input["soil"]["salt"]["Ca"];
                $soil_salt_detail["Mg"] = $input["soil"]["salt"]["Mg"];
                $soil_salt_detail["K"] = $input["soil"]["salt"]["K"];
                $soil_salt_detail["Na"] = $input["soil"]["salt"]["Na"];
                $soil_salt_detail["Na2CO3"] = $input["soil"]["salt"]["Na2CO3"];
                $saved_soil_salt_detail = $this->saltDetailRepository->save_localized($soil_salt_detail, $farm->soil_detail->salt_detail_id);
                //soil
                $soil_detail['salt_detail_id'] = $saved_soil_salt_detail->id;
                $soil_detail['type'] = "soil";
                $soil_detail['acidity_type_id'] = $input["soil"]["acidity_type_id"];
                $soil_detail['acidity_value'] = $input["soil"]["acidity_value"];
                $soil_detail['acidity_unit_id'] = $input["soil"]["acidity_unit_id"];
                $soil_detail['salt_type_id'] = $input["soil"]["salt_type_id"];
                $soil_detail['salt_concentration_value'] = $input["soil"]["salt_concentration_value"];
                $soil_detail['salt_concentration_unit_id'] = $input["soil"]["salt_concentration_unit_id"] ?? null;
                $saved_soil_detail = $this->chemicalDetailRepository->save_localized($soil_detail, $farm->soil_detail_id);
                $farm_detail['soil_detail_id'] = $saved_soil_detail->id;

                //irrigation.salt
                $irrigation_salt_detail["saltable_type"] = "irrigation";
                $irrigation_salt_detail["PH"] = $input["irrigation"]["salt"]["PH"];
                $irrigation_salt_detail["CO3"] = $input["irrigation"]["salt"]["CO3"];
                $irrigation_salt_detail["HCO3"] = $input["irrigation"]["salt"]["HCO3"];
                $irrigation_salt_detail["Cl"] = $input["irrigation"]["salt"]["Cl"];
                $irrigation_salt_detail["SO4"] = $input["irrigation"]["salt"]["SO4"];
                $irrigation_salt_detail["Ca"] = $input["irrigation"]["salt"]["Ca"];
                $irrigation_salt_detail["Mg"] = $input["irrigation"]["salt"]["Mg"];
                $irrigation_salt_detail["K"] = $input["irrigation"]["salt"]["K"];
                $irrigation_salt_detail["Na"] = $input["irrigation"]["salt"]["Na"];
                $irrigation_salt_detail["Na2CO3"] = $input["irrigation"]["salt"]["Na2CO3"];
                $saved_irrigation_salt_detail = $this->saltDetailRepository->save_localized($irrigation_salt_detail, $farm->irrigation_water_detail->salt_detail_id);
                //irrigation
                $irrigation_detail['salt_detail_id'] = $saved_irrigation_salt_detail->id;
                $irrigation_detail['type'] = "irrigation";
                $irrigation_detail['acidity_type_id'] = $input["irrigation"]["acidity_type_id"];
                $irrigation_detail['acidity_value'] = $input["irrigation"]["acidity_value"];
                $irrigation_detail['acidity_unit_id'] = $input["irrigation"]["acidity_unit_id"];
                $irrigation_detail['salt_type_id'] = $input["irrigation"]["salt_type_id"];
                $irrigation_detail['salt_concentration_value'] = $input["irrigation"]["salt_concentration_value"];
                $irrigation_detail['salt_concentration_unit_id'] = $input["irrigation"]["salt_concentration_unit_id"] ?? null;
                $saved_irrigation_detail = $this->chemicalDetailRepository->save_localized($irrigation_detail, $farm->irrigation_water_detail_id);
                $farm_detail['irrigation_water_detail_id'] = $saved_irrigation_detail->id;
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
                //drink.salt
                $drink_salt_detail["saltable_type"] = "drink";
                $drink_salt_detail["PH"] = $input["drink"]["salt"]["PH"];
                $drink_salt_detail["CO3"] = $input["drink"]["salt"]["CO3"];
                $drink_salt_detail["HCO3"] = $input["drink"]["salt"]["HCO3"];
                $drink_salt_detail["Cl"] = $input["drink"]["salt"]["Cl"];
                $drink_salt_detail["SO4"] = $input["drink"]["salt"]["SO4"];
                $drink_salt_detail["Ca"] = $input["drink"]["salt"]["Ca"];
                $drink_salt_detail["Mg"] = $input["drink"]["salt"]["Mg"];
                $drink_salt_detail["K"] = $input["drink"]["salt"]["K"];
                $drink_salt_detail["Na"] = $input["drink"]["salt"]["Na"];
                $drink_salt_detail["Na2CO3"] = $input["drink"]["salt"]["Na2CO3"];
                $saved_drink_salt_detail = $this->saltDetailRepository->save_localized($drink_salt_detail, $farm->animal_drink_water_salt_detail_id);
                $farm_detail['animal_drink_water_salt_detail_id'] = $saved_drink_salt_detail->id;
            }

            $farm = $this->farmRepository->save_localized($farm_detail, $id);

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

            return $this->sendResponse(new FarmResource($farm), 'Farm updated successfully');
        }catch(\Throwable $th){
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

        $farm->location()->delete();
        $farm->soil_detail()->delete();
        $farm->irrigation_water_detail()->delete();
        $farm->animal_drink_water_salt_detail()->delete();
        $farm->posts()->delete();
        $farm->service_tables()->delete();
        $farm->service_tasks()->delete();
        $farm->delete();

          return $this->sendSuccess('Model deleted successfully');
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
