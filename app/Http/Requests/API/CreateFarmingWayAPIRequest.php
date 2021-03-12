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
            'name_ar_localized' => ['required','max:200', new UniqueTranslationRule($request->all(), 'farming_ways', 'type')],
            'name_en_localized' => ['required','max:200', new UniqueTranslationRule($request->all(), 'farming_ways', 'type')],
            'type' => ['required']
        ];
        // return FarmingWay::$rules;
    }
}
