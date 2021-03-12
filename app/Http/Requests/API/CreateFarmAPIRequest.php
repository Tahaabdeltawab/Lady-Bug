<?php

namespace App\Http\Requests\API;

use App\Models\Farm;
use InfyOm\Generator\Request\APIRequest;

class CreateFarmAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'real'                                  => 'required',
            'archived'                              => 'required',
            'location'                              => 'required',
            'farming_date'                          => 'required',
            'farming_compatibility'                 => 'required',
            'farm_activity_type_id'                 => 'required|exists:farm_activity_types,id', // 1-crops, 2-trees, 3-homeplants, 4-animals
            'farmed_type_id'                        => 'required|exists:farmed_types,id',
            'farmed_type_class_id'                  => 'required|exists:farmed_type_classes,id',
            //crops, trees
            'soil_type_id'                          => 'requiredIf:farm_activity_type_id,1,2|exists:soil_types,id',
            'irrigation_way_id'                     => 'requiredIf:farm_activity_type_id,1,2|exists:irrigation_ways,id',
            'soil'                                  => 'array|requiredIf:farm_activity_type_id,1,2',
            'soil.*'                                => 'requiredIf:farm_activity_type_id,1,2',
            'soil.salt'                             => 'array|requiredIf:farm_activity_type_id,1,2',
            'soil.salt.*'                           => 'requiredIf:farm_activity_type_id,1,2',
            'irrigation'                            => 'array|requiredIf:farm_activity_type_id,1,2',
            'irrigation.*'                          => 'requiredIf:farm_activity_type_id,1,2',
            'irrigation.salt'                       => 'array|requiredIf:farm_activity_type_id,1,2',
            'irrigation.salt.*'                     => 'requiredIf:farm_activity_type_id,1,2',
            //animals
            "animal_medicine_sources"               => "array|requiredIf:farm_activity_type_id,4",
            "animal_medicine_sources.*"             => "requiredIf:farm_activity_type_id,4|exists:animal_medicine_sources,id",
            "animal_fodder_sources"                 => "array|requiredIf:farm_activity_type_id,4",
            "animal_fodder_sources.*"               => "requiredIf:farm_activity_type_id,4|exists:animal_fodder_sources,id",
            "animal_fodder_types"                   => "array|requiredIf:farm_activity_type_id,4",
            "animal_fodder_types.*"                 => "requiredIf:farm_activity_type_id,4|exists:animal_fodder_types,id",
            'animal_breeding_purpose_id'            => 'requiredIf:farm_activity_type_id,4|exists:animal_breeding_purposes,id',
            'drink.salt'                            => 'array|requiredIf:farm_activity_type_id,1,2',
            'drink.salt.*'                          => 'requiredIf:farm_activity_type_id,1,2',
            //crops, trees, animals
            'area'                                  => 'requiredIf:farm_activity_type_id,1,2,4',
            'area_unit_id'                          => 'requiredIf:farm_activity_type_id,1,2,4',
            //crops, trees, homeplants
            "chemical_fertilizer_sources"           => "array|requiredIf:farm_activity_type_id,1,2,3",
            "chemical_fertilizer_sources.*"         => "requiredIf:farm_activity_type_id,1,2,3|exists:chemical_fertilizer_sources,id",
            "seedling_sources"                      => "array|requiredIf:farm_activity_type_id,1,2,3",
            "seedling_sources.*"                    => "requiredIf:farm_activity_type_id,1,2,3|exists:seedling_sources,id",
            //homeplant, trees, animals
            "farmed_number"                         => "requiredIf:farm_activity_type_id,2,3,4|integer",
            //homeplants
            'home_plant_illuminating_source_id'     => 'requiredIf:farm_activity_type_id,3|exists:home_plant_illuminating_sources,id',
            'home_plant_pot_size'                   => 'requiredIf:farm_activity_type_id,3',
            //crops
            'farming_method_id'                     => 'requiredIf:farm_activity_type_id,1|exists:farming_methods,id',
            //crops, animals
            'farming_way_id'                        => 'requiredIf:farm_activity_type_id,1,4|exists:farming_ways,id',    
        ];

                // FIELDS UNDER SOIL, IRRIGATION, DRINK(ONLY SALTS)
                /*'type' => 'requiredIf:farm_activity_type_id,1,2',
                'acidity' => 'requiredIf:farm_activity_type_id,1,2',
                'acidity_value' => 'requiredIf:farm_activity_type_id,1,2',
                'acidity_unit_id' => 'requiredIf:farm_activity_type_id,1,2',
                'salt_type' => 'requiredIf:farm_activity_type_id,1,2',
                'salt_concentration_value' => 'requiredIf:farm_activity_type_id,1,2',
                'salt_concentration_unit_id' => 'requiredIf:farm_activity_type_id,1,2',
                'salt' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.saltable_type' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.PH' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.CO3' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.HCO3' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.Cl' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.SO4' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.Ca' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.Mg' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.K' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.Na' => 'requiredIf:farm_activity_type_id,1,2',
                    'salt.Na2CO3' => 'requiredIf:farm_activity_type_id,1,2', */
    
    }
}
