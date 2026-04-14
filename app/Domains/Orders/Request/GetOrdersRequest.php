<?php

namespace App\Domains\Orders\Request;

use Illuminate\Foundation\Http\FormRequest;

class GetOrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('sales')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|integer',
            'date_from'   => 'nullable|date',
            'date_to'     => 'nullable|date|after_or_equal:date_from',
            'limit'       => 'nullable|integer|min:1',
            'page'        => 'nullable|integer|min:1',
            'status'      => 'nullable|string|in:pending,confirmed,shipped,delivered,cancelled',
        ];
    }
}
