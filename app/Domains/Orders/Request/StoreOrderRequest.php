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
            'customer_id'     => 'required|integer',
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|string|max:50',
            'order_date'      => 'required|date',
            'amount_total'    => 'required|numeric',
            'amount_tax'      => 'required|numeric',
            'payment_status'  => 'required|string',
            'delivery_status' => 'nullable|string',
            'notes'           => 'nullable|string',
        ];
    }
}
