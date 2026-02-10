<?php

namespace App\Domains\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sales')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'commercial_name' => 'nullable|string|max:255',
            'taxtation_name'  => 'nullable|string|max:255',
            'email'           => 'nullable|email|unique:customers,email',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:255',
            'zone'            => 'required|string|max:100',
            'city'            => 'required|string|max:100',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
        ];
    }
}
