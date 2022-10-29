<?php

namespace App\Http\Requests\API;

use App\Models\ServiceTask;
use InfyOm\Generator\Request\APIRequest;

class UpdateServiceTaskAPIRequest extends APIRequest
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
        return  [
            'name' => 'required|max:30',
            'start_at' => 'required|date_format:Y-m-d',
            'notify_at' => 'required|date_format:Y-m-d',
            'due_at' => 'nullable|date_format:Y-m-d',
            'task_type_id' => 'required|exists:task_types,id',
            'quantity' => 'nullable',
            'quantity_unit_id' => 'required|exists:measuring_units,id',
        ];
    }
}
