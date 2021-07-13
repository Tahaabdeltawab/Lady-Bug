<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;
use App\Models\FarmedType;
use App\Models\FarmedTypeClass;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\CreateFarmAPIRequest;
use App\Http\Requests\API\UpdateFarmAPIRequest;
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
use App\Http\Resources\FarmedTypeClassResource;
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

use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmResource;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Http\Helpers\WeatherApi;

class FarmAPIController extends AppBaseController
{

    private $farmRepository;
    private $userRepository;
    private $saltDetailRepository;
    private $chemicalDetailRepository;
    private $postRepository;

    private $acidityTypeRepository;
    private $saltTypeRepository;
    private $homePlantPotSizeRepository;
    private $farmActivityTypeRepository;
    private $farmedTypeClassRepository;
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
    }


    // // // // WEATHER // // // //

    public function get_weather(Request $request)
    {
        $resp = WeatherApi::instance()->weather_api($request);

        return $resp['status'] ?
        $this->sendResponse($resp['data'] , 'Weather data retrieved successfully')
        :
        $this->sendError('Error fetching the weather data', $resp['data']['cod'], $resp['data']['message']);
    }

    // // // // COMPATIBILITY // // // //

    protected function avg($array)
    {
        return count($array) ? array_sum($array) / count($array) : 0;
    }

    protected function min_max($array)
    {
        if(count($array))
        {
            $min = $array[0] < $array[1] ? $array[0] : $array[1];
            $max = $min == $array[0] ? $array[1] : $array[0];
            return [$min, $max];
        }
        return false;
    }

    protected function in_range($num, $array)
    {
        if(count($array))
        {
            $min = $this->min_max($array)[0];
            $max = $this->min_max($array)[1];
            return ($num >= $min && $num <= $max);
        }
        else
        {
            return false;
        }
    }

    protected function calc_soil_type($soil_type_id, $suitable_soil_types, $full_deg)
    {
        $deg = 0;
        if((count($suitable_soil_types) > 0) && in_array($soil_type_id, $suitable_soil_types))
        {
            $deg = $soil_type_id == $suitable_soil_types[0] ? 0.5 : 0.25;
        }

        return $deg = $deg * $full_deg;
    }

    protected function calc_deg_avg($value, $model, $full_deg) // get degree in things whose best degree is the average value
    {
        $deg = 0;
        if((count($model) > 0))
        {
            if($this->in_range($value, $model))
            {
                $best   = $this->avg($model);
                $min    = $this->min_max($model)[0];
                $max    = $this->min_max($model)[1];
                $deg    = $value < $best ? ($value - $min) / ($best - $min) : ($max - $value) / ($max - $best);
            }
        }

        return $deg = $deg * $full_deg;
    }

    protected function calc_deg_min($value, $model, $full_deg) // get degree in things whose best degree is the minimum value
    {
        $deg = 0;
        if((count($model) > 0))
        {
            if($this->in_range($value, $model))
            {
                $best   = $this->min_max($model)[0];
                $min    = $this->min_max($model)[0];
                $max    = $this->min_max($model)[1];
                $deg    = ($max - $value) / ($max - $best);
            }
        }

        return $deg = $deg * $full_deg;
    }

    public function calculate_compatibility($id)
    {
        $farm = \App\Models\Farm::with(['farmed_type', 'soil_detail', 'irrigation_water_detail'])->find($id);

        if (empty($farm))
        {
            return $this->sendError('Farm not found');
        }

        if (!in_array($farm->farm_activity_type_id, [1, 2])) // farm must be crops or trees
        {
            return $this->sendError('Farm type not valid');
        }

        $soil_type_deg = $this->calc_soil_type($farm->soil_type_id, $farm->farmed_type->suitable_soil_types, 5);

        $soil_salt_deg = $this->calc_deg_min($farm->soil_detail->salt_concentration_value, $farm->farmed_type->suitable_soil_salts_concentration, 20);

        $water_salt_deg = $this->calc_deg_min($farm->irrigation_water_detail->salt_concentration_value, $farm->farmed_type->suitable_water_salts_concentration, 25);

        $ph_deg = $this->calc_deg_avg($farm->soil_detail->acidity_value, $farm->farmed_type->suitable_ph, 5);




        $farming_day    = date("Y-m-d", strtotime($farm->farming_date . ' - 1 year'));

        $flowering_day1  = date("Y-m-d", strtotime($farm->farming_date . ' + ' . ($farm->farmed_type->flowering_time - 10) . ' days'));
        $flowering_day2  = date("Y-m-d", strtotime($farm->farming_date . ' + ' . ($farm->farmed_type->flowering_time - 05) . ' days'));
        $flowering_day3  = date("Y-m-d", strtotime($farm->farming_date . ' + ' . ($farm->farmed_type->flowering_time - 00) . ' days'));
        $flowering_day4  = date("Y-m-d", strtotime($farm->farming_date . ' + ' . ($farm->farmed_type->flowering_time + 05) . ' days'));
        $flowering_day5  = date("Y-m-d", strtotime($farm->farming_date . ' + ' . ($farm->farmed_type->flowering_time + 10) . ' days'));

        $maturity_day   = date("Y-m-d", strtotime($farm->farming_date . ' + ' . $farm->farmed_type->maturity_time . ' days'));

        $lat = $farm->location->latitude;
        $lon = $farm->location->longitude;

        $farming_info   = WeatherApi::instance()->weather_history($lat, $lon, $farming_day);

        $flowering_info1 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day1);
        $flowering_info2 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day2);
        $flowering_info3 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day3);
        $flowering_info4 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day4);
        $flowering_info5 = WeatherApi::instance()->weather_history($lat, $lon, $flowering_day5);

        $maturity_info  = WeatherApi::instance()->weather_history($lat, $lon, $maturity_day);

        $farming_temperature = $farming_info['temperature'];
        $flowering_temperature_average = ($flowering_info1['temperature'] + $flowering_info2['temperature'] + $flowering_info3['temperature'] + $flowering_info4['temperature'] + $flowering_info5['temperature']) / 5 ;
        $maturity_temperature = $maturity_info['temperature'];
        $humidity = $maturity_info['humidity'];

        $tfarming_deg = $this->calc_deg_avg($farming_temperature, $farm->farmed_type->farming_temperature, 20);

        $tflowering_deg = $this->calc_deg_avg($flowering_temperature_average, $farm->farmed_type->flowering_temperature, 15);

        $tmaturity_deg = $this->calc_deg_avg($maturity_temperature, $farm->farmed_type->maturity_temperature, 5);

        $hmaturity_deg = $this->calc_deg_avg($humidity, $farm->farmed_type->humidity, 5);


        $total = $soil_type_deg + $soil_salt_deg + $water_salt_deg + $ph_deg + $tfarming_deg + $tflowering_deg + $tmaturity_deg + $hmaturity_deg;
        // $total = 'soil_type_deg = ' . $soil_type_deg . ' - ' . 'soil_salt_deg = ' . $soil_salt_deg . ' - ' . 'water_salt_deg = ' . $water_salt_deg . ' - ' . 'ph_deg = ' . $ph_deg . ' - ' . 'tfarming_deg = ' . $tfarming_deg . ' - ' . 'tflowering_deg = ' . $tflowering_deg . ' - ' . 'tmaturity_deg = ' . $tmaturity_deg . ' - ' . 'hmaturity_deg = ' . $hmaturity_deg;

        $msg = '';
        if($total < 50)
        {
            $msg = "incompatible. ";
        }
        else
        {
            $msg = "compatible. ";
        }
        if($water_salt_deg == 0)
        {
            $msg = "incompatible because of non suitable water salts concentration. ";
        }
        if($soil_salt_deg == 0)
        {
            $msg .= "incompatible because of non suitable soil salts concentration. ";
        }
        if($tflowering_deg == 0)
        {
            $msg .= "incompatible because of non suitable flowering temperature. ";
        }


        return $this->sendResponse(["total" => $total], $msg);

        // farmed_type->flowering_time,
        // farmed_type->maturity_time,
    }


    public function index(Request $request)
    {
        try{
            $farms = $this->farmRepository->all();

            return $this->sendResponse(['all' => FarmResource::collection($farms)], 'Farms retrieved successfully');
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

            $data['seedling_sources'] = SeedlingSourceResource::collection($this->seedlingSourceRepository->all());
            $data['chemical_fertilizer_sources'] = ChemicalFertilizerSourceResource::collection($this->chemicalFertilizerSourceRepository->all());
            $data['animal_fodder_sources'] = AnimalFodderSourceResource::collection($this->animalFodderSourceRepository->all());
            $data['animal_medicine_sources'] = AnimalMedicineSourceResource::collection($this->animalMedicineSourceRepository->all());

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
            $archived_farms = auth()->user()->allTeams()->where('archived', true);
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
            $farm_detail['admin_id'] = auth()->id();
            $farm_detail['real'] = $input["real"];
            $farm_detail['archived'] = $input["archived"];
            $farm_detail['farm_activity_type_id'] = $input["farm_activity_type_id"];
            $farm_detail['farmed_type_id'] = $input["farmed_type_id"];
            $farm_detail['farmed_type_class_id'] = $input["farmed_type_class_id"] ?? null;
            $farm_detail['farming_date'] = $input["farming_date"];
            $farm_detail['farming_compatibility'] = $input["farming_compatibility"];
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

            auth()->user()->attachRole('farm-admin', $farm);

            return $this->sendResponse(new FarmResource($farm), 'Farm saved successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function app_roles(Request $request)
    {
        $roles = Role::farmAllowedRoles()->get();
        return $this->sendResponse(['all' =>  RoleResource::collection($roles)], 'Roles retrieved successfully');
    }


    public function app_users(Request $request)
    {
        $farm = $this->farmRepository->find($request->farm);
        if (empty($farm))
        {
            return $this->sendError('Farm not found');
        }

        $farm_users = $farm->users->pluck('id');
        $farm_users[] = auth()->id();
        $users = User::whereNotIn('id', $farm_users)->whereHas('roles', function($q){
            $q->where('name', config('myconfig.user_default_role'));
        })->get();

        return $this->sendResponse(['all' => UserResource::collection($users)], 'Users retrieved successfully');
    }

    // attach a farm role to a user who has an invitation link
    public function first_attach_farm_role(Request $request)
    {
        try
        {
            if (! $request->hasValidSignature() || !$request->user || !$request->role || !$request->farm) {
                return $this->sendError('Wrong url', 401);
            }

            $user = $this->userRepository->find($request->user);
            $farm = $this->farmRepository->find($request->farm);

            $user->attachRole($request->role, $farm);

            DB::table('notifications')
            ->where('type', 'App\Notifications\FarmInvitation')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $request->user)
            ->where('data->invitee', $request->user)
            ->where('data->farm', $request->farm)
            ->where('data->role', $request->role)
            ->update(['data->accepted' => true]);

            return $this->sendResponse(new UserResource($user), __('Member added to farm successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    //attach, edit or delete farm roles (send empty or no role_id when deleting a role)
    public function update_farm_role(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'farm' => 'integer|required|exists:farms,id',
                'user' => 'integer|required|exists:users,id',
                'role' => 'nullable|integer|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return $this->sendError(json_encode($validator->errors()));
            }

            $user = $this->userRepository->find($request->user);
            $farm = $this->farmRepository->find($request->farm);


            if($request->role)   //first attach or edit roles
            {
                $role = Role::find($request->role);
                if(!in_array($role->name, config('myconfig.farm_allowed_roles')))
                {
                    return $this->sendError('Invalid Role');
                }

                if($user->get_roles($request->farm)) //edit roles
                {
                    $user->syncRoles([$request->role], $farm);
                }
                else            // first attach role
                {
                    //send invitation to assignee user

                    $user->notify(new \App\Notifications\FarmInvitation(
                        auth()->user(),
                        $role,
                        $farm,
                        URL::temporarySignedRoute('api.farms.roles.first_attach', now()->addDays(10),
                            [
                                'user' => $request->user,
                                'farm' => $request->farm,
                                'role' => $request->role,
                            ])
                        ));
                    return $this->sendSuccess(__('Invitation sent successfully'));
                }

            }
            else                    //delete roles
            {
                if($user->get_roles($request->farm)){
                    $user->detachRoles([], $farm);
                }else{
                    return $this->sendError(__('This user is not a member in this farm'), 7000);
                }
            }

            return $this->sendResponse(new UserResource($user), __('Farm roles saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function get_farm_users($id)
    {
        try
        {
            /** @var Farm $farm */
            $farm = $this->farmRepository->find($id);

            if (empty($farm))
            {
                return $this->sendError('Farm not found');
            }

            $users = $farm->users;
            return $this->sendResponse(['all' => UserResource::collection($users)], 'Farm users retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function get_farm_posts($id)
    {
        try
        {
            /** @var Farm $farm */
            $farm = $this->farmRepository->find($id);

            if (empty($farm))
            {
                return $this->sendError('Farm not found');
            }

            $posts = $farm->posts()->accepted()->get();
            $postResource = new PostResource($posts);
            return $this->sendResponse(['all' => $postResource->collection($posts)], 'Farm posts retrieved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
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
            $farm_detail['real'] = $input["real"];
            $farm_detail['archived'] = $input["archived"];
            $farm_detail['farm_activity_type_id'] = $input["farm_activity_type_id"];
            $farm_detail['farmed_type_id'] = $input["farmed_type_id"];
            $farm_detail['farmed_type_class_id'] = $input["farmed_type_class_id"] ?? null;
            $farm_detail['farming_date'] = $input["farming_date"];
            $farm_detail['farming_compatibility'] = $input["farming_compatibility"];

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
