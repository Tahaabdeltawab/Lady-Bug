<?php

namespace App\Http\Requests\API;

use App\Models\DiseaseRegistration;
use InfyOm\Generator\Request\APIRequest;

class UpdateDiseaseRegistrationAPIRequest extends APIRequest
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
        $rules = DiseaseRegistration::$update_rules;

        return $rules;
    }
}
