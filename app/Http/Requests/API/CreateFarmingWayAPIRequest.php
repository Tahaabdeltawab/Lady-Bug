<?php

namespace App\Http\Requests\API;

use App\Models\FarmingWay;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Http\Request;
use App\Rules\UniqueTranslationRule;

class CreateFarmingWayAPIRequest extends APIRequest
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
    public function rules(Request $request)
    {
        return[
            'name.ar' => 'required|max:30',
            'name.en' => 'required|max:30',
            'type' => 'required|in:farming,breeding'
        ];
    }
}
