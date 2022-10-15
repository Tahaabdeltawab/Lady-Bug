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
            'business_id'                           => 'required|exists:businesses,id',
            'real'                                  => 'required',
            'archived'                              => 'required',
            'location'                              => 'array|required',
            'location.latitude'                     => 'required',
            'location.longitude'                    => 'required',
            'location.country'                      => 'nullable',
            'location.city'                         => 'nullable',
            'location.district'                     => 'nullable',
            'location.details'                      => 'nullable',
            'farming_date'                          => 'required|date_format:Y-m-d',//|after_or_equal:' . date('Y-m-d'),
            'farm_activity_type_id'                 => 'required|exists:farm_activity_types,id',
            // 1-crops, 2-trees, 3-homeplants, 4-animals
            'farmed_type_id'                        => 'required|exists:farmed_types,id',
            'farmed_type_class_id'                  => 'nullable|exists:farmed_type_classes,id',  //|exists:farmed_type_classes,id => deleted this validation because if the farmed_type has no classes, the farm saving request will send it as 0
            //crops, trees 1,2
            'soil_type_id'                          => 'requiredIf:farm_activity_type_id,1,2|exists:soil_types,id',
            'irrigation_way_id'                     => 'requiredIf:farm_activity_type_id,1,2|exists:irrigation_ways,id',
            'area'                                  => 'requiredIf:farm_activity_type_id,1,2',
            'area_unit_id'                          => 'requiredIf:farm_activity_type_id,1,2',

            'soil'                                  => 'array|requiredIf:farm_activity_type_id,1,2',
            'soil.salt'                             => 'array|requiredIf:farm_activity_type_id,1,2',
            'soil.acidity_type_id'                  => 'requiredIf:farm_activity_type_id,1,2',
            'soil.acidity_value'                    => 'requiredIf:farm_activity_type_id,1,2|numeric|max:14|min:0',
            'soil.acidity_unit_id'                  => 'requiredIf:farm_activity_type_id,1,2',
            'soil.salt_type_id'                     => 'requiredIf:farm_activity_type_id,1,2',
            'soil.salt_concentration_value'         => 'requiredIf:farm_activity_type_id,1,2',
            // 'soil.salt_concentration_unit_id'       => 'requiredIf:farm_activity_type_id,1,2',
            'soil.salt_concentration_unit_id'       => 'nullable',
            'soil.salt'                             => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.PH'                      => 'requiredIf:farm_activity_type_id,1,2|numeric|max:14|min:0',
                'soil.salt.CO3'                     => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.HCO3'                    => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.Cl'                      => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.SO4'                     => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.Ca'                      => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.Mg'                      => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.K'                       => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.Na'                      => 'requiredIf:farm_activity_type_id,1,2',
                'soil.salt.Na2CO3'                  => 'requiredIf:farm_activity_type_id,1,2',



            'irrigation'                            => 'array|requiredIf:farm_activity_type_id,1,2',
            'irrigation.salt'                       => 'array|requiredIf:farm_activity_type_id,1,2',
            'irrigation.acidity_type_id'            => 'requiredIf:farm_activity_type_id,1,2',
            'irrigation.acidity_value'                    => 'requiredIf:farm_activity_type_id,1,2|numeric|max:14|min:0',
            'irrigation.acidity_unit_id'                  => 'requiredIf:farm_activity_type_id,1,2',
            'irrigation.salt_type_id'                        => 'requiredIf:farm_activity_type_id,1,2',
            'irrigation.salt_concentration_value'         => 'requiredIf:farm_activity_type_id,1,2',
            // 'irrigation.salt_concentration_unit_id'       => 'requiredIf:farm_activity_type_id,1,2',
            'irrigation.salt_concentration_unit_id'       => 'nullable',
            'irrigation.salt'                             => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.PH'                      => 'requiredIf:farm_activity_type_id,1,2|numeric|max:14|min:0',
                'irrigation.salt.CO3'                     => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.HCO3'                    => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.Cl'                      => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.SO4'                     => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.Ca'                      => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.Mg'                      => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.K'                       => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.Na'                      => 'requiredIf:farm_activity_type_id,1,2',
                'irrigation.salt.Na2CO3'                  => 'requiredIf:farm_activity_type_id,1,2',

            //animals 4
            "animal_medicine_sources"               => "array",
            "animal_medicine_sources.*"             => "exists:businesses,id",
            "animal_fodder_sources"                 => "array",
            "animal_fodder_sources.*"               => "exists:businesses,id",
            "animal_fodder_types"                   => "array",
            "animal_fodder_types.*"                 => "exists:animal_fodder_types,id",
            'animal_breeding_purpose_id'            => 'requiredIf:farm_activity_type_id,4|exists:animal_breeding_purposes,id',
            'drink.salt'                            => 'array|requiredIf:farm_activity_type_id,4',
                'drink.salt.PH'                      => 'requiredIf:farm_activity_type_id,4|numeric|max:14|min:0',
                'drink.salt.CO3'                     => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.HCO3'                    => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.Cl'                      => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.SO4'                     => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.Ca'                      => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.Mg'                      => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.K'                       => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.Na'                      => 'requiredIf:farm_activity_type_id,4',
                'drink.salt.Na2CO3'                  => 'requiredIf:farm_activity_type_id,4',

            //crops, trees, homeplants 1,2,3
            "chemical_fertilizer_sources"           => "array",
            "chemical_fertilizer_sources.*"         => "exists:businesses,id",
            "seedling_sources"                      => "array",
            "seedling_sources.*"                    => "exists:businesses,id",
            //homeplant, trees, animals 2,3,4
            "farmed_number"                         => "requiredIf:farm_activity_type_id,2,3,4|integer",
            //homeplants 3
            'home_plant_illuminating_source_id'     => 'requiredIf:farm_activity_type_id,3|exists:home_plant_illuminating_sources,id',
            'home_plant_pot_size_id'                => 'requiredIf:farm_activity_type_id,3|exists:home_plant_pot_sizes,id',
            //crops 1
            'farming_method_id'                     => 'requiredIf:farm_activity_type_id,1|exists:farming_methods,id',
            //crops, animals 1,4
            'farming_way_id'                        => 'requiredIf:farm_activity_type_id,1,4|exists:farming_ways,id',
        ];
    }
}
