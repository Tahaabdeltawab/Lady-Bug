<?php

namespace App\Http\Requests\API;

use App\Models\MarketingData;
use InfyOm\Generator\Request\APIRequest;

class UpdateMarketingDataAPIRequest extends APIRequest
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
        $rules = MarketingData::$rules;
        
        return $rules;
    }
}
