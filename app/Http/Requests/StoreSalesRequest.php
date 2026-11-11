<?php

namespace App\Http\Requests;

use App\Models\Sales;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        // A soft-deleted salesman with the same email is restored on creation,
        // so its row must not trigger uniqueness errors.
        $trashedId = Sales::onlyTrashed()->where('email', $this->input('email'))->value('id');

        return [
            'name'                     => 'required|string|max:255',
            'email'                    => ['required', 'email', 'max:255', Rule::unique('sales', 'email')->whereNull('deleted_at')],
            'phone'                    => ['required', 'string', 'max:20', Rule::unique('sales', 'phone')->ignore($trashedId)],
            'national_number'          => ['nullable', 'string', 'max:14', Rule::unique('sales', 'national_number')->ignore($trashedId)],
            'address'                  => 'nullable|string',
            'zone'                     => 'nullable|string|max:255',
            'city'                     => 'nullable|string|max:255',
            'country_odoo_id'          => 'required|integer',
            'state_odoo_id'            => 'required|integer',
            'storage_location_odoo_id' => 'required|integer',
            'allowed_city_ids'         => 'required|array|min:1',
            'allowed_city_ids.*'       => 'required|integer',
            'password'                 => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'The phone field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'allowed_city_ids.required' => 'Please select at least one city for this salesman.',
        ];
    }
}
