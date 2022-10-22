<?php

namespace App\Http\Requests\API;

use App\Models\Comment;
use InfyOm\Generator\Request\APIRequest;

class RateProductRequest extends APIRequest
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
            'rating' => ['required', 'numeric', 'max:5', 'min:1'],
            'product' => ['required', 'integer', 'exists:products,id']
        ];
    }
}
