<?php

namespace App\Http\Requests\API;

use App\Models\SaltType;
use InfyOm\Generator\Request\APIRequest;
use App\Rules\UniqueTranslationRule;
use Illuminate\Http\Request;

class CreateSaltTypeAPIRequest extends APIRequest
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
        $id = $this->salt_type ?? null;
        return [
            'name_ar_localized' => ['required','max:200', new UniqueTranslationRule($request->all(), 'salt_types', 'type', $id)],
            'name_en_localized' => ['required','max:200', new UniqueTranslationRule($request->all(), 'salt_types', 'type', $id)],
            'type' => ['required']
        ];
    }
}
