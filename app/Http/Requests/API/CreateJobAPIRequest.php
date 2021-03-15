<?php

namespace App\Http\Requests\API;

use App\Models\HumanJob;
use InfyOm\Generator\Request\APIRequest;

class CreateJobAPIRequest extends APIRequest
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
        $id = $this->job ?? null;
        return [
            'name_ar_localized' => 'required|max:200|unique:human_job_translations,name,'.$id.',human_job_id,locale,ar',
            'name_en_localized' => 'required|max:200|unique:human_job_translations,name,'.$id.',human_job_id,locale,en',
        ];
    }
}
