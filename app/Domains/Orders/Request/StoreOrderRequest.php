<?php

namespace App\Domains\Orders\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_id'           => 'required|integer',
            'customer_name'         => 'required|string|max:255',
            'customer_phone'        => 'required|string|max:50',
            'delivery_date'         => 'required|date|after_or_equal:today',
            'amount_total'          => 'required|numeric',
            'payment_method'        => 'required|string|in:cash,credit_card,bank_transfer',
            'payment_status'        => 'required|string',
            'delivery_status'       => 'nullable|string',
            'notes'                 => 'nullable|string',
            'products'              => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.quantity'   => 'required|integer',
            'products.*.discount'   => 'nullable|numeric',
        ];
    }
}
