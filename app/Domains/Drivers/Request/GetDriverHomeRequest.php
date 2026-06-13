<?php

namespace App\Domains\Drivers\Request;

use Illuminate\Foundation\Http\FormRequest;

class GetDriverHomeRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->guard('drivers')->check();
    }

    public function rules()
    {
        return [
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
        ];
    }
}
