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
        $id = $this->farming_way ?? null;
        return[
            'name_ar_localized' => ['required','max:200', new UniqueTranslationRule($request->all(), 'farming_ways', 'type', $id)],
            'name_en_localized' => ['required','max:200', new UniqueTranslationRule($request->all(), 'farming_ways', 'type', $id)],
            'type' => ['required']
        ];       
    }
}
