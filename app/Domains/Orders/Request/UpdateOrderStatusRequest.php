<?php

namespace App\Domains\Orders\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // Ensure the user is authenticated
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'     => 'required|integer|exists:orders,id',
            'status' => 'required|string|in:accepted,delivering,delivered,cancelled'
        ];
    }
}
