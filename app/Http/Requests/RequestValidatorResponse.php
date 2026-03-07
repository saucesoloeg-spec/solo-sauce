<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestValidatorResponse extends FormRequest
{
    /**
     * When we fail validation, override our default error.
     *
     * @param ValidatorContract $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'response_code'    => 422,
                'response_message' => $this->validator->errors()->first(),
                'response_data'    => $this->validator->errors(),
            ], 422)
        );
    }
}
