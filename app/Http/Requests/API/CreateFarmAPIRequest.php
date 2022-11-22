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
            // 'farmed_type_class_id'                  => 'nullable|exists:farmed_type_classes,id',  //|exists:farmed_type_classes,id => deleted this validation because if the farmed_type has no classes, the farm saving request will send it as 0
            //crops, trees 1,2
            'soil_type_id'                          => 'requiredIf:farm_activity_type_id,1,2|exists:soil_types,id',
            'irrigation_way_id'                     => 'requiredIf:farm_activity_type_id,1,2|exists:irrigation_ways,id',
            'area'                                  => 'requiredIf:farm_activity_type_id,1,2',
            'area_unit_id'                          => 'requiredIf:farm_activity_type_id,1,2',

            'soil'                                  => 'nullable',
            'soil.salt'                             => 'nullable',
            'soil.acidity_type_id'                  => 'nullable',
            'soil.acidity_value'                    => 'nullable|numeric|max:14|min:0',
            'soil.acidity_unit_id'                  => 'nullable',
            'soil.salt_type_id'                     => 'nullable',
            'soil.salt_concentration_value'         => 'nullable',
            // 'soil.salt_concentration_unit_id'       => 'nullable',
            'soil.salt_concentration_unit_id'       => 'nullable',
            'soil.salt'                             => 'nullable',
                'soil.salt.PH'                      => 'nullable|numeric|max:14|min:0',
                'soil.salt.CO3'                     => 'nullable',
                'soil.salt.HCO3'                    => 'nullable',
                'soil.salt.Cl'                      => 'nullable',
                'soil.salt.SO4'                     => 'nullable',
                'soil.salt.Ca'                      => 'nullable',
                'soil.salt.Mg'                      => 'nullable',
                'soil.salt.K'                       => 'nullable',
                'soil.salt.Na'                      => 'nullable',
                'soil.salt.Na2CO3'                  => 'nullable',



            'irrigation'                            => 'nullable',
            'irrigation.salt'                       => 'nullable',
            'irrigation.acidity_type_id'            => 'nullable',
            'irrigation.acidity_value'                    => 'nullable|numeric|max:14|min:0',
            'irrigation.acidity_unit_id'                  => 'nullable',
            'irrigation.salt_type_id'                        => 'nullable',
            'irrigation.salt_concentration_value'         => 'nullable',
            // 'irrigation.salt_concentration_unit_id'       => 'nullable',
            'irrigation.salt_concentration_unit_id'       => 'nullable',
            'irrigation.salt'                             => 'nullable',
                'irrigation.salt.PH'                      => 'nullable|numeric|max:14|min:0',
                'irrigation.salt.CO3'                     => 'nullable',
                'irrigation.salt.HCO3'                    => 'nullable',
                'irrigation.salt.Cl'                      => 'nullable',
                'irrigation.salt.SO4'                     => 'nullable',
                'irrigation.salt.Ca'                      => 'nullable',
                'irrigation.salt.Mg'                      => 'nullable',
                'irrigation.salt.K'                       => 'nullable',
                'irrigation.salt.Na'                      => 'nullable',
                'irrigation.salt.Na2CO3'                  => 'nullable',

            //animals 4
            "animal_medicine_sources"               => "array",
            "animal_medicine_sources.*"             => "exists:businesses,id",
            "animal_fodder_sources"                 => "array",
            "animal_fodder_sources.*"               => "exists:businesses,id",
            "animal_fodder_types"                   => "array",
            "animal_fodder_types.*"                 => "exists:animal_fodder_types,id",
            'animal_breeding_purpose_id'            => 'requiredIf:farm_activity_type_id,4|exists:animal_breeding_purposes,id',
            'drink.salt'                            => 'nullable',
                'drink.salt.PH'                      => 'nullable|numeric|max:14|min:0',
                'drink.salt.CO3'                     => 'nullable',
                'drink.salt.HCO3'                    => 'nullable',
                'drink.salt.Cl'                      => 'nullable',
                'drink.salt.SO4'                     => 'nullable',
                'drink.salt.Ca'                      => 'nullable',
                'drink.salt.Mg'                      => 'nullable',
                'drink.salt.K'                       => 'nullable',
                'drink.salt.Na'                      => 'nullable',
                'drink.salt.Na2CO3'                  => 'nullable',

            //crops, trees, homeplants 1,2,3
            "chemical_fertilizer_sources"           => "nullable|array",
            "chemical_fertilizer_sources.*"         => "exists:businesses,id",
            "seedling_sources"                      => "nullable|array",
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
