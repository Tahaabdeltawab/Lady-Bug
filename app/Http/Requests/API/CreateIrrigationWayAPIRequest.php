<?php

namespace App\Http\Requests\API;

use App\Models\IrrigationWay;
use InfyOm\Generator\Request\APIRequest;

class CreateIrrigationWayAPIRequest extends APIRequest
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
        $id = $this->irrigation_way ?? null;
        return [
            'name_ar_localized' => 'required|max:200',
            'name_en_localized' => 'required|max:200',
        ];
    }
}
