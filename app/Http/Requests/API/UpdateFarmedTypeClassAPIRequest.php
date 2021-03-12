<?php

namespace App\Http\Requests\API;

use App\Models\FarmedTypeClass;
use InfyOm\Generator\Request\APIRequest;

class UpdateFarmedTypeClassAPIRequest extends APIRequest
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
        $rules = FarmedTypeClass::$rules;
        
        return $rules;
    }
}
