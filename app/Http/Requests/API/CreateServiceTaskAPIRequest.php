<?php

namespace App\Http\Requests\API;

use App\Models\ServiceTask;
use InfyOm\Generator\Request\APIRequest;

class CreateServiceTaskAPIRequest extends APIRequest
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
            'name' => 'required|max:200',
            'start_at' => 'required|date_format:Y-m-d',
            'notify_at' => 'required|date_format:Y-m-d|before_or_equal:start_at',
            'due_at' => 'nullable|date_format:Y-m-d|after:start_at',
            'task_type_id' => 'required|exists:task_types,id',
            'quantity' => 'required',
            'quantity_unit_id' => 'nullable|exists:measuring_units,id',
            'farm_id' => 'required|exists:farms,id',
            'service_table_id' => 'required|exists:service_tables,id',
        ];
    }
}
