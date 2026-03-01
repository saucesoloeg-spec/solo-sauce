<?php

namespace App\Domains\Surveys\Services;

use App\Domains\Surveys\Repositories\SurveyRepository;

class SurveyService
{
    protected $survey_repository;

    public function __construct(SurveyRepository $survey_repository)
    {
        $this->survey_repository = $survey_repository;
    }

    public function getAllQuestions()
    {
        $questions = $this->survey_repository->all();

        if ($questions) {
            return [
                'response_code'    => 200,
                'response_message' => 'Questions retrieved successfully',
                'response_data'    => $questions
            ];
        }
        
        return [
            'response_code'    => 404,
            'response_message' => 'No questions found',
            'response_data'    => null
        ];
    }

    public function storeSurveyAnswers($request)
    {
        $store = $this->survey_repository->store($request);

        if ($store) {
            return [
                'response_code'    => 201,
                'response_message' => 'Survey answers stored successfully',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Failed to store survey answers',
            'response_data'    => null
        ];
    }

    public function getSurveyAnswersByCustomerId($customer_id)
    {
        $answers = $this->survey_repository->getAnswersByCustomerId($customer_id);

        if ($answers) {
            return [
                'response_code'    => 200,
                'response_message' => 'Survey answers retrieved successfully',
                'response_data'    => $answers
            ];
        }
        
        return [
            'response_code'    => 404,
            'response_message' => 'No survey answers found for this customer',
            'response_data'    => null
        ];
    }   
}