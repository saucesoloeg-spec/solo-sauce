<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
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
            'sales_id'    => 'required|exists:sales,id',
            'customer_id' => 'required|exists:customers,id',
            'visit_date'  => 'required|after_or_equal:today',
            'notes'       => 'nullable|string'
        ];
    }
}
