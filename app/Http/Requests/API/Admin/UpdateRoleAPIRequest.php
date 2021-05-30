<?php

namespace App\Http\Requests\API\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleAPIRequest extends FormRequest
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
            'name'          => ["required", "unique:roles,name," . $this->role, "string", "max:255"],
            "display_name" => ["required", "string", "max:255"],
            "description" => ["nullable", "string", "max:255"],
            "permissions" => ['nullable', 'array'],
            'permissions.*' => ["nullable", "exists:permissions,id"],
        ];
    }
}
