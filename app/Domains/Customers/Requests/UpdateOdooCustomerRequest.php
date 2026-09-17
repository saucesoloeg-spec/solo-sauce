<?php

namespace App\Domains\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOdooCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return auth('sales')->check();
    }

    public function rules()
    {
        return [
            'name'       => 'sometimes|string|max:255',
            'phone'      => 'sometimes|nullable|string|max:255',
            'mobile'     => 'sometimes|nullable|string|max:255',
            'email'      => 'sometimes|nullable|email|max:255',
            'street'     => 'sometimes|nullable|string|max:255',
            'address'    => 'sometimes|nullable|string|max:255',
            'ref'        => 'sometimes|nullable|string|max:255',
            'zip_code'   => 'sometimes|nullable|string|max:255',
            'country_id' => 'sometimes|nullable|integer|min:1',
            'state_id'   => 'sometimes|nullable|integer|min:1',
            'city_id'    => 'sometimes|nullable|integer|min:1',
            'latitude'   => 'sometimes|nullable|numeric',
            'longitude'  => 'sometimes|nullable|numeric',
            'customer_rank' => 'sometimes|integer',
            'is_company' => 'sometimes|boolean',
            'company_id' => 'sometimes|nullable',
        ];
    }
}
