<?php

namespace App\Domains\Odoo\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'available_only' => 'boolean',
            'category_id'    => 'integer|exists:categories,id',
            'limit'          => 'integer|min:1|max:100',
            'page'           => 'integer|min:1',
            'search'         => 'string|max:255',
        ];
    }
}
