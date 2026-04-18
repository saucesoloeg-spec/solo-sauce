<?php

namespace App\Domains\Surveys\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetSurveyAnswersRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'visit_id'    => 'required|integer',
        ];
    }
}
