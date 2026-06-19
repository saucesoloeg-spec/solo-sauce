<?php

namespace App\Domains\Orders\Request;

use Illuminate\Foundation\Http\FormRequest;

class OrderProductDeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('drivers')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'                    => 'required|integer|exists:orders,id',
            'products'              => 'required|array',
            'products.*.product_id' => 'required|integer|exists:order_products,product_id',
            'products.*.quantity'   => 'required|integer|min:1',
        ];
    }
}
