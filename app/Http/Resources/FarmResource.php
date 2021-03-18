<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fat_id = $this->farm_activity_type_id;

         //all 1,2,3,4
         $farm_detail['id'] = $this->id;
         $farm_detail['admin_id'] = $this->admin_id;
         $farm_detail['real'] = $this->real;
         $farm_detail['archived'] = $this->archived;
         $farm_detail['farm_activity_type'] = new FarmActivityTypeResource($this->farm_activity_type);
         $farm_detail['farmed_type'] = new FarmedTypeResource($this->farmed_type);
         $farm_detail['farmed_type_class'] = new FarmedTypeClassResource($this->farmed_type_class);
         $farm_detail['location'] = new LocationResource($this->location);
         $farm_detail['farming_date'] = date('Y-m-d', strtotime($this->farming_date));
         $farm_detail['farming_compatibility'] = $this->farming_compatibility;

         //crops 1
         if($fat_id == 1)
         {
             $farm_detail['farming_method'] = new FarmingMethodResource($this->farming_method);
         }

         //crops, animals 1,4
         if($fat_id == 1 || $fat_id == 4)
         {
             $farm_detail['farming_way'] = new FarmingWayResource($this->farming_way);
         }
         
         //crops, trees, animals 1,2,4
         if($fat_id == 1 || $fat_id == 2 || $fat_id == 4)
         {
             $farm_detail['area'] = $this->area;
             $farm_detail['area_unit'] = new MeasuringUnitResource($this->area_unit);
         }

         //crops, trees 1,2
         if($fat_id == 1 || $fat_id == 2)
         {
             $farm_detail['irrigation_way'] = new IrrigationWayResource($this->irrigation_way);
             $farm_detail['soil_type'] = new SoilTypeResource($this->soil_type);
             $farm_detail['soil_detail'] = new ChemicalDetailResource($this->soil_detail);
             $farm_detail['irrigation_water_detail'] = new ChemicalDetailResource($this->irrigation_water_detail);
         }

         //homeplant, trees, animals 2,3,4
         if($fat_id == 2 || $fat_id == 3 || $fat_id == 4)
         {
             $farm_detail['farmed_number'] = $this->farmed_number;
         }

         //homeplants 3
         if($fat_id == 3)
         {
             $farm_detail['home_plant_pot_size'] = new HomePlantPotSizeResource($this->home_plant_pot_size);
             $farm_detail['home_plant_illuminating_source'] = new HomePlantIlluminatingSourceResource($this->home_plant_illuminating_source);
         }

         // animal 4
         if($fat_id == 4)
         {
             $farm_detail['animal_breeding_purpose'] = new AnimalBreedingPurposeResource($this->animal_breeding_purpose);
             $farm_detail['animal_drink_water_salt_detail'] = new SaltDetailResource($this->animal_drink_water_salt_detail);
             $farm_detail['animal_medicine_sources'] =  AnimalMedicineSourceResource::collection($this->animal_medicine_sources);
             $farm_detail['animal_fodder_sources'] =  AnimalFodderSourceResource::collection($this->animal_fodder_sources);
             $farm_detail['animal_fodder_types'] =  AnimalFodderTypeResource::collection($this->animal_fodder_types);
         }           

         //crops, trees, homeplants 1,2,3
         if($fat_id == 1 || $fat_id == 2 || $fat_id == 3)
         {
            $farm_detail['chemical_fertilizer_sources'] = ChemicalFertilizerSourceResource::collection($this->chemical_fertilizer_sources);
            $farm_detail['seedling_sources'] =  SeedlingSourceResource::collection($this->seedling_sources);
         }

         // pass the farm to the usercollection to add the farm_roles to the collection
         // if you pass a farm in the collection() you will get farm_roles property in the users collection and vice versa
         $farm_detail['users'] = (new UserResource($this->users))->collection($this->users)->farm($this);
        //  $farm_detail['users'] = UserResource::collection($this->users);
         $farm_detail['posts'] = PostResource::collection($this->posts);
         $farm_detail['service_tables'] = ServiceTableResource::collection($this->service_tables);
         $farm_detail['coming_task'] = $this->service_tasks()->orderBy('start_at', 'asc')->first() ?? (object) [] ;
       
         return $farm_detail;

        /* return [
            'id' => $this->id,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'real' => $this->real,
            'archived' => $this->archived,
            'location' => $this->location,
            'farming_date' => $this->farming_date,
            'farming_compatibility' => $this->farming_compatibility,
            'home_plant_pot_size_id' => $this->home_plant_pot_size,
            'area' => $this->area,
            'area_unit_id' => $this->area_unit_id,
            'farm_activity_type' => $this->farm_activity_type,
            'farmed_type' => $this->farmed_type,
            'farmed_type_class' => $this->farmed_type_class,
            'farmed_number' => $this->farmed_number,
            'breeding_purpose' => $this->breeding_purpose,
            'home_plant_illuminating_source' => $this->home_plant_illuminating_source,
            'farming_method' => $this->farming_method,
            'farming_way' => $this->farming_way,
            'irrigation_way' => $this->irrigation_way,
            'soil_type' => $this->soil_type,
            'soil_detail' => $this->soil_detail,
            'irrigation_water_detail' => $this->irrigation_water_detail,
            'animal_drink_water_salt_detail' => $this->animal_drink_water_salt_detail
        ]; */
    }
}
