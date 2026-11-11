<?php

namespace App\Domains\Surveys\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyAnswersRequest extends FormRequest
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
            'sales_notes'           => 'nullable|string|max:5000',
            'customer_id'           => 'required_with:sales_notes|integer',
            'answers'               => 'required_without:sales_notes|array',
            'answers.*.survey_id'   => 'required|exists:surveys,id',
            'answers.*.answer'      => 'required|string', // if question is dropdown send the option value as string
            'answers.*.customer_id' => 'required|integer',
            'answers.*.note'        => 'nullable|string|max:255',
        ];
    }
}
