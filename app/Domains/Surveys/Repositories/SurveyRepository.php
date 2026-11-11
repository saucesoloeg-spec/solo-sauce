<?php

namespace App\Domains\Surveys\Repositories;

use App\Models\Customer;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SalesCustomer;

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
        $answers     = $data['answers'] ?? [];
        $sales_notes = $data['sales_notes'] ?? null;
        $customer_id = $data['customer_id'] ?? $answers[0]['customer_id']; // Assuming all answers have the same customer_id
        $has_answers = !empty($answers);

        $visit = $this->sales_customers_model->where('customer_id', $customer_id)
                                             ->whereDate('visit_at', date('Y-m-d'))
                                             ->where('status', 'pending')
                                             ->first();

        if ($visit) {
            $update = ['survey' => $has_answers, 'status' => 'completed'];
            if ($sales_notes !== null) {
                $update['sales_notes'] = $sales_notes;
            }
            $this->sales_customers_model->where('id', $visit->id)->update($update);
        }
        else {
            $visit = $this->sales_customers_model->create([
                'sales_id'          => auth('sales')->id(),
                'customer_id'       => $customer_id,
                'visit_at'          => now(),
                'survey'            => $has_answers,
                'status'            => 'completed',
                'sales_notes'       => $sales_notes,
            ]);
        }

        if (!$has_answers) {
            $answers = $this->model->pluck('id')->map(function ($survey_id) use ($customer_id) {
                return ['survey_id' => $survey_id, 'customer_id' => $customer_id, 'answer' => null];
            })->all();
        }

        foreach ($answers as $answer) {
            $answer['sales_id']          = auth('sales')->id();
            $answer['sales_customer_id'] = $visit->id; 
            $this->answers_model->create($answer);
        }
        
        return true;
    }

    public function getAnswersByCustomerId($data)
    {
        if (empty($data['visit_id'])) {
            return $this->getAnswersGroupedByVisit($data['customer_id']);
        }

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
                    ->get()
                    ->each(function ($item) use ($visit) {
                        $item->sales_notes = $visit->sales_notes;
                    });
    }

    private function getAnswersGroupedByVisit($customer_id)
    {
        $surveys = $this->model->all();

        $visits = $this->sales_customers_model
                    ->where('customer_id', $customer_id)
                    ->where(function ($q) {
                        $q->whereHas('answers')->orWhereNotNull('sales_notes');
                    })
                    ->with('answers')
                    ->orderByDesc('visit_at')
                    ->get();

        return $visits->map(function ($visit) use ($surveys) {
            $answers = $visit->answers->keyBy('survey_id');

            return [
                'visit_id' => $visit->id,
                'visit_at' => $visit->visit_at,
                'sales_notes' => $visit->sales_notes,
                'surveys'  => $surveys->map(function ($survey) use ($answers) {
                    $item = $survey->toArray();
                    $item['answer'] = optional($answers->get($survey->id))->answer;

                    return $item;
                })->values(),
            ];
        })->values();
    }

    public function getAnswersBySalesId($id, $filters = [])
    {
        return Customer::with([
            'answers' => function ($q) use ($id, $filters) {
                $q->where('sales_id', $id)
                ->with(['survey', 'visit']);
                if(isset($filters['from']) && isset($filters['to'])) {
                    $q->whereBetween('created_at', [$filters['from'], $filters['to']]);
                }
            }
        ])
        ->whereHas('answers', function ($q) use ($id, $filters) {
            $q->where('sales_id', $id);
            if(isset($filters['from']) && isset($filters['to'])) {
                $q->whereBetween('created_at', [$filters['from'], $filters['to']]);
            }
        })
        ->get()
        ->each(function ($customer) {
            $grouped = $customer->answers
                ->groupBy('sales_customer_id')
                ->map(function ($answers, $visit_id) {
                    return [
                        'visit_id' => $visit_id,
                        'visit_at' => optional($answers->first()->visit)->visit_at,
                        'sales_notes' => optional($answers->first()->visit)->sales_notes,
                        'answers'  => $answers->map(function ($answer) {
                            return $answer->makeHidden('visit');
                        })->values(),
                    ];
                })
                ->sortByDesc('visit_at')
                ->values();

            $customer->setRelation('answers', $grouped);
        });
    }

}