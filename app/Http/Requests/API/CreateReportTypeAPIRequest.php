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
            'name_ar_localized' => 'required|max:200|unique:report_type_translations,name,'.$id.',report_type_id,locale,ar',
            'name_en_localized' => 'required|max:200|unique:report_type_translations,name,'.$id.',report_type_id,locale,en',
        ];
    }
}
