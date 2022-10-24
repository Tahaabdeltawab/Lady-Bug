<?php

namespace App\Http\Requests\API;

use App\Models\FarmingWay;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Http\Request;
use App\Rules\UniqueTranslationRule;

class UpdateFarmingWayAPIRequest extends APIRequest
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
        // $id = last($request->segments()); //primitive way to get the url parameters
        // print(json_encode($this->route())); //from this, you can know the parameter name {farming_way}
        // $id = $this->farming_way ?? null;
        return[
            'name' => ['required'],
            'type' => ['required']
        ];
    }
}
