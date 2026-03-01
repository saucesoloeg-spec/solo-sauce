<?php

namespace App\Domains\Surveys\Repositories;

use App\Models\Survey;
use App\Models\SurveyAnswer;

class SurveyRepository
{
    private $model;
    private $answers_model;

    public function __construct(Survey $model, SurveyAnswer $answers_model)
    {
        $this->model = $model;
        $this->answers_model = $answers_model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function store($data)
    {
        // Assuming $data is an array of answers
        foreach ($data['answers'] as $answer) {
            $this->answers_model->create($answer);
        }

        return true;
    }

    public function getAnswersByCustomerId($customer_id)
    {
       return $this->model->leftJoin('survey_answers', function ($join) use ($customer_id) {
                        $join->on('surveys.id', '=', 'survey_answers.survey_id')
                            ->where('survey_answers.customer_id', '=', $customer_id);
                    })
                    ->select('surveys.*', 'survey_answers.answer')
                    ->get();
    }
}