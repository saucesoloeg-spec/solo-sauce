<?php

namespace App\Domains\Surveys\Repositories;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SalesCustomer;
use Illuminate\Support\Facades\DB;

class SurveyRepository
{
    private $model;
    private $answers_model;
    private $sales_customers_model;

    public function __construct(Survey $model, SurveyAnswer $answers_model, SalesCustomer $sales_customers_model)
    {
        $this->model = $model;
        $this->answers_model = $answers_model;
        $this->sales_customers_model = $sales_customers_model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function store($data)
    {
        $customer_id = $data['answers'][0]['customer_id']; // Assuming all answers have the same customer_id
        
        $visit = $this->sales_customers_model->where('customer_id', $customer_id)
                                             ->whereDate('visit_at', date('Y-m-d'))
                                             ->where('status', 'pending')
                                             ->first();

        if ($visit) {
            $update_visit = $this->sales_customers_model->where('id', $visit->id)->update(['survey' => true, 'status' => 'completed']);
        }
        else {
            $visit = $this->sales_customers_model->create([
                'sales_id'          => auth('sales')->id(),
                'customer_id'       => $customer_id,
                'visit_at'          => now(),
                'survey'            => true,
                'status'            => 'completed',
            ]);
        }

        // Assuming $data is an array of answers
        foreach ($data['answers'] as $answer) {
            $answer['sales_id']          = auth('sales')->id();
            $answer['sales_customer_id'] = $visit->id; 
            $this->answers_model->create($answer);
        }
        
        return true;
    }

    public function getAnswersByCustomerId($data)
    {
        $visit = $this->sales_customers_model->where('id', $data['visit_id'])->first();
        if (!$visit) {
            return null; // or throw an exception
        }

        return $this->model->leftJoin('survey_answers', function ($join) use ($data, $visit) {
                        $join->on('surveys.id', '=', 'survey_answers.survey_id')
                            ->where('survey_answers.customer_id', '=', $data['customer_id'])
                            ->whereDate('survey_answers.created_at', '=', date('Y-m-d', strtotime($visit['visit_at'])));
                    })
                    ->select('surveys.*', 'survey_answers.answer')
                    ->get();
    }

    public function getAnswersBySalesId($id)
    {
        return DB::table('survey_answers as a')
                 ->join('sales_customers as sc', 'sc.id', '=', 'a.sales_customer_id')
                 ->join('surveys as s', 's.id', '=', 'a.survey_id') // optional if you want question
                 ->select(
                     'a.*',
                     'sc.visit_at',
                     's.*' // optional
                 )
                 ->where('a.sales_id', $id) 
                 ->orderBy('a.sales_customer_id')
                 ->get()
                 ->groupBy('sales_customer_id');
    }

}