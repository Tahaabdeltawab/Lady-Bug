<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;

class CreateBusinessRoleRequest extends APIRequest
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
            // because not read because in params not query request
            // 'business' => 'required|exists:businesses,id',
            // 'user' => 'required|exists:users,id',
            // 'role' => 'nullable|exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'period' => 'nullable',
            'plan_id' => 'nullable|exists:offline_consultancy_plans,id',
            // nullable here for skipping date_format rule in date is null
            'start_date' => 'required_with:end_date|nullable|date_format:Y-m-d',
            'end_date' => 'required_with:start_date|nullable|date_format:Y-m-d',
        ];
    }
}
