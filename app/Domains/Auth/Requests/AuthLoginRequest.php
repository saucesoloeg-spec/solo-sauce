<?php

namespace App\Domains\Auth\Requests;

use App\Models\Driver;
use App\Models\Sales;
use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
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
            // 'uuid'     => 'required|string|max:7',
            'email'    => ['required', 'email', function ($attribute, $value, $fail) {
                if (!Sales::where('email', $value)->exists() && !Driver::where('email', $value)->exists()) {
                    $fail('The selected '.$attribute.' does not exist.');
                }
            }],
            'password' => 'required|string',
        ];
    }
}
