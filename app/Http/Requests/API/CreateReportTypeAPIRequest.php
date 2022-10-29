<?php

namespace App\Http\Requests\API;

use App\Models\ReportType;
use InfyOm\Generator\Request\APIRequest;

class CreateReportTypeAPIRequest extends APIRequest
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
        $id = $this->report_type ?? null;
        return [
            'name.ar' => 'required|max:30',
            'name.en' => 'required|max:30',
        ];
    }
}
