<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;

class UpdateProfileAPIRequest extends APIRequest
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
        $id = auth()->id();
        return [
            'photo' => ['nullable', 'max:5000', 'mimes:jpeg,jpg,png'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,$id,id"],
            'human_job_id' => ['required', 'exists:human_jobs,id'],
            'bio' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date_format:Y-m-d'],
            'marital_status' => ['nullable'],
            'educations' => ['nullable', 'array'],
            'careers' => ['nullable', 'array'],
            'residences' => ['nullable', 'array'],
            'visiteds' => ['nullable', 'array'],

            'mobile' => ['nullable', 'string', 'max:255', "unique:users,mobile,$id,id"],
            'income' => ['nullable', 'integer', 'min:0'],
            'city_id' => ['nullable', 'exists:cities,id'],
        ];
    }
}
