<?php

namespace App\Http\Requests\API;

use App\Models\FarmingMethod;
use InfyOm\Generator\Request\APIRequest;

class CreateFarmingMethodAPIRequest extends APIRequest
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
            'name_ar_localized' => 'required|max:200|unique:farming_method_translations,name,'.null.',id,locale,ar',
            'name_en_localized' => 'required|max:200|unique:farming_method_translations,name,'.null.',id,locale,en',
        ];
    }
}
