<?php

namespace InfyOm\Generator\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Response;

class APIRequest extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

     // not used after laravel 5.4. https://stackoverflow.com/questions/47690817/laravel-5-5-validation-change-format-of-response-when-validation-fails
  /*   public function response(array $errors)
    {
        $messages = implode(' ', Arr::flatten($errors));

        return Response::json(ResponseUtil::makeError($messages), 400);
    } */


    protected function failedValidation(Validator $validator)
    {
        // $messages = [];
        // $errors = $validator->errors();
        // foreach ($errors as $error) {
        //     array_push($messages, $error);
        // }

    throw new HttpResponseException(response()->json(ResponseUtil::makeError($validator->errors()->first(), 422, $validator->errors())));

    // return Response::json(ResponseUtil::makeError($messages , 400), 400);
    }
}
