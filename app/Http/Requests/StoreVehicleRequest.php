<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'brand'         => 'required|string|max:100',
            'model'         => 'required|string|max:100',
            'color'         => 'required|string|max:50',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'driver_id'     => 'nullable|exists:drivers,id',
            'deputy_id'     => 'nullable|exists:deputies,id',
        ];
    }

    public function messages()
    {
        return [
            'brand.required'         => 'The brand field is required.',
            'model.required'         => 'The model field is required.',
            'color.required'         => 'The color field is required.',
            'license_plate.required' => 'The license plate field is required.',
            'license_plate.unique'   => 'This license plate is already in use.',
        ];
    }
}
