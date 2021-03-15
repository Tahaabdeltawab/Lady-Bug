<?php

namespace App\Http\Controllers\API;


use App\Models\Role;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\CreateFarmAPIRequest;
use App\Http\Requests\API\UpdateFarmAPIRequest;
use App\Repositories\FarmRepository;
use App\Repositories\UserRepository;
use App\Repositories\SaltDetailRepository;
use App\Repositories\ChemicalDetailRepository;
use App\Repositories\WorkableRepository;
use App\Repositories\LocationRepository;
use App\Repositories\PostRepository;

use App\Repositories\SaltTypeRepository;
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

use App\Http\Resources\SaltTypeResource;
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


class FarmAPIController extends AppBaseController
{
    
    private $farmRepository;
    private $userRepository;
    private $workableRepository;
    private $saltDetailRepository;
    private $chemicalDetailRepository;
    private $postRepository;

    private $saltTypeRepository;
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
        WorkableRepository $workableRepo,
        SaltDetailRepository $saltDetailRepo, 
        ChemicalDetailRepository $chemicalDetailRepo,
        PostRepository $postRepo,

        SaltTypeRepository $saltTypeRepo,
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
        $this->workableRepository = $workableRepo;
        $this->saltDetailRepository = $saltDetailRepo;
        $this->chemicalDetailRepository = $chemicalDetailRepo;
        $this->postRepository = $postRepo;
        
        $this->saltTypeRepository = $saltTypeRepo;
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
            $data['salt_types'] = SaltTypeResource::collection($this->saltTypeRepository->all());
            $data['farm_activity_types'] = FarmActivityTypeResource::collection($this->farmActivityTypeRepository->all());
            $data['farmed_type_classes'] = FarmedTypeClassResource::collection($this->farmedTypeClassRepository->all());
            $data['farmed_types'] = FarmedTypeResource::collection($this->farmedTypeRepository->all());
            $data['area_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'area'])->all());
            $data['acidity_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'acidity'])->all());
            $data['salt_concentration_units'] = MeasuringUnitResource::collection($this->measuringUnitRepository->where(['measurable' => 'salt_concentration'])->all());
            $data['irrigation_ways'] = IrrigationWayResource::collection($this->irrigationWayRepository->all());
            $data['home_plant_illuminating_sources'] = HomePlantIlluminatingSourceResource::collection($this->homePlantIlluminatingSourceRepository->all());
            $data['farming_ways'] = FarmingWayResource::collection($this->farmingWayRepository->all());
            $data['animal_breeding_purposes'] = AnimalBreedingPurposeResource::collection($this->animalBreedingPurposeRepository->all());
            $data['farming_methods'] = FarmingMethodResource::collection($this->farmingMethodRepository->all());
            $data['seedling_sources'] = SeedlingSourceResource::collection($this->seedlingSourceRepository->all());
            $data['chemical_fertilizer_sources'] = ChemicalFertilizerSourceResource::collection($this->chemicalFertilizerSourceRepository->all());
            $data['animal_fodder_types'] = AnimalFodderTypeResource::collection($this->animalFodderTypeRepository->all());
            $data['animal_fodder_sources'] = AnimalFodderSourceResource::collection($this->animalFodderSourceRepository->all());
            $data['animal_medicine_sources'] = AnimalMedicineSourceResource::collection($this->animalMedicineSourceRepository->all());
            $data['soil_types'] = SoilTypeResource::collection($this->soilTypeRepository->all());

            return $this->sendResponse(['all' => $data], 'Farms relations retrieved successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }


    public function store(CreateFarmAPIRequest $request)
    {
        try{

            $input = $request->validated();
            
            $fat_id = $input["farm_activity_type_id"];
            
            //all 1,2,3,4
            $farm_detail['real'] = $input["real"];
            $farm_detail['archived'] = $input["archived"];
            $farm_detail['farm_activity_type_id'] = $input["farm_activity_type_id"];
            $farm_detail['farmed_type_id'] = $input["farmed_type_id"];
            $farm_detail['farmed_type_class_id'] = $input["farmed_type_class_id"];
            $farm_detail['farming_date'] = $input["farming_date"];
            $farm_detail['farming_compatibility'] = $input["farming_compatibility"];

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
            
            //crops, trees, animals 1,2,4
            if($fat_id == 1 || $fat_id == 2 || $fat_id == 4)
            {
                $farm_detail['area'] = $input["area"];
                $farm_detail['area_unit_id'] = $input["area_unit_id"];
            }

            //crops, trees 1,2
            if($fat_id == 1 || $fat_id == 2)
            {
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
                $soil_detail['salt_concentration_unit_id'] = $input["soil"]["salt_concentration_unit_id"];
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
                $irrigation_detail['salt_concentration_unit_id'] = $input["irrigation"]["salt_concentration_unit_id"];
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
                $farm_detail['home_plant_pot_size'] = $input["home_plant_pot_size"];
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

            //set the farm workers adding the creator to them
           /*  $workers = $request->workers ?? [];
            $workers[] = auth()->id();
            $farm->workers()->sync($workers);
    
            //set the farm workers roles being the creator the admin
            $admin_role_id = \App\Models\WorkableRole::select('id')->where('name','admin')->whereHas('workable_type', function($q){
                $q->where('name', 'App\Models\Farm');
            })->first()->id;
    
            foreach($farm->workers as $farm_worker){
                $workable_roles[$farm_worker->id] = $request->{"workable_roles_".$farm_worker->id} ?? [];
                if($farm_worker->id == auth()->id()){
                    $workable_roles[$farm_worker->id] = [$admin_role_id];
                }
                $workables[$farm_worker->id] = \App\Models\Workable::where([['worker_id',$farm_worker->id], ['workable_id',$farm->id], ['workable_type','App\Models\Farm']])
                                             ->first()->workable_roles()->sync($workable_roles[$farm_worker->id]);
            } */
          
            auth()->user()->attachRole('farm-admin', $farm);

            return $this->sendResponse(new FarmResource($farm), 'Farm saved successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }


    public function roles_index()
    {
        try
        {
            $roles = Role::whereNotIn('name', ['app-user','app-admin'])->get();
            return $this->sendResponse(['all' => RoleResource::collection($roles)], 'Roles retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }


    // attach a farm role to a user who has an invitation link
    public function first_attach_farm_role(Request $request)
    {
        try
        {
            if (! $request->hasValidSignature() || !$request->user_id || !$request->role_id || !$request->farm_id) {
                return $this->sendError('Wrong url', 401);
            }

            $user = $this->userRepository->find($request->user_id);
            $farm = $this->farmRepository->find($request->farm_id);
            
            $user->attachRole($request->role_id, $farm);

            return $this->sendSuccess(__('Member added to farm successfully'));
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
                'farm_id' => 'integer|required|exists:farms,id',
                'user_id' => 'integer|required|exists:users,id',
                'role_id' => 'nullable|integer|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return $this->sendError(json_encode($validator->errors()));
            }

            $user = $this->userRepository->find($request->user_id);
            $farm = $this->farmRepository->find($request->farm_id);
            
            if($request->role_id)   //first attach or edit roles
            {
                if($user->getRoles($farm)) //edit roles
                {
                    $user->syncRoles([$request->role_id], $farm);
                }
                else            // first attach role
                {
                    //send invitation to assignee user
                    $role = Role::find($request->role_id);
                    $user->notify(new \App\Notifications\FarmInvitation(
                        auth()->user(),
                        $role,
                        $farm,
                        URL::temporarySignedRoute('api.farms.roles.first_attach', now()->addDays(10), 
                            [
                                'user_id' => $request->user_id,
                                'farm_id' => $request->farm_id,
                                'role_id' => $request->role_id,
                            ])
                        )); 
                    return $this->sendSuccess(__('Invitation sent successfully'));
                }
                
            }
            else                    //delete roles
            {
                if($user->getRoles($farm)){
                    $user->detachRoles([], $farm);
                }else{
                    return $this->sendError(__('This user is not a member in this farm'), 7000); 
                }            
            }
            
            return $this->sendSuccess(__('Farm roles saved successfully'));
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
            $userResource = new UserResource($users);
            // $user_farm_role = $user->getRoles($farm);
            // return $this->sendResponse($users, 'Farm users retrieved successfully');
            return $this->sendResponse(['all' => $userResource->collection($users)], 'Farm users retrieved successfully');
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

            $posts = $farm->posts;
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

            $farm = $this->farmRepository->save_localized($input, $id);

            //set the farm workers adding the creator to them
            $workers = $request->workers ?? [];
            $workers[] = auth()->id();
            $farm->workers()->sync($workers);


            //set the farm workers roles being the creator the admin
            $admin_role_id = \App\Models\WorkableRole::select('id')->where('name','admin')->whereHas('workable_type', function($q){
                $q->where('name', 'App\Models\Farm');
            })->first()->id;

            foreach($farm->workers as $farm_worker){
                $workable_roles[$farm_worker->id] = $request->{"workable_roles_".$farm_worker->id} ?? [];
                // if($farm_worker->id == auth()->id()){
                //     $workable_roles[$farm_worker->id] = [$admin_role_id];
                // }
                $workables[$farm_worker->id] = \App\Models\Workable::where([['worker_id',$farm_worker->id], ['workable_id',$farm->id], ['workable_type','App\Models\Farm']])
                                            ->first()->workable_roles()->sync($workable_roles[$farm_worker->id]);
            }

            return $this->sendResponse(new FarmResource($farm), 'Farm updated successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }

    }

    public function destroy($id)
    {
        try{
            /** @var Farm $farm */
            $farm = $this->farmRepository->find($id);

            if (empty($farm)) {
                return $this->sendError('Farm not found');
            }

            $farm->delete();

            return $this->sendSuccess('Farm deleted successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }




    public function create()
    {
        // $workers = resolve($this->userRepository->model())->where('id','!=',auth()->id())->get();
        $workers = $this->userRepository->all();
        $workable_roles = \App\Models\WorkableRole::whereHas('workable_type', function($q){// the same collect($worker->farms->find($farm->id)->pivot->workable_roles)->whereHas...
            $q->where('name', 'App\Models\Farm');
        })->get();
        return view('farms.create', compact('workers', 'workable_roles'));
    }


    public function edit($id)
    {
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {
            Flash::error('Farm not found');

            return redirect(route('farms.index'));
        }

        // $workers = resolve($this->userRepository->model())->where('id','!=',auth()->id())->get();
        $workers = $this->userRepository->all();
        $workableHasWorkers = $farm->workers->pluck('id')->all();
        $workable_roles = \App\Models\WorkableRole::whereHas('workable_type', function($q){// the same collect($worker->farms->find($farm->id)->pivot->workable_roles)->whereHas...
            $q->where('name', 'App\Models\Farm');
        })->get();

        return view('farms.edit', compact('farm', 'workers', 'workableHasWorkers', 'workable_roles'));
    }



   /*  public function edit_roles($id)
    {
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {

            Flash::error('Farm with this worker not found');

            return redirect(route('farms.index'));
        }

        $workers = $farm->workers;

        return view('farms.roles.edit', compact('farm', 'workers'));
    }


    public function update_roles($id, Request $request)
    {
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {

            Flash::error('Farm with this worker not found');

            return redirect(route('farms.index'));
        }

        $workable_roles = $request->workable_roles ?? [];

        $workable->workable_roles()->sync($workable_roles);

        Flash::success('Farm roles updated successfully.');

        return redirect(route('farms.index'));
    }
 */

}
