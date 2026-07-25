<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeputyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:deputies,email',
            'phone' => 'nullable|string|max:50|unique:deputies,phone',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
