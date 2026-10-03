<?php

namespace App\Domains\Sales\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status'      => 'required|string|in:pending,completed,cancelled',
            'sales_notes' => 'nullable|string|max:5000',
        ];
    }
}
