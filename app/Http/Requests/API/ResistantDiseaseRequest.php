<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;

class ResistantDiseaseRequest extends APIRequest
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
            'farmed_type_id'    => 'required|exists:farmed_types,id',
            'diseases'          => 'nullable|array',
            'diseases.*'        => 'exists:diseases,id',
        ];
    }
}
