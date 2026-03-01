<?php

namespace App\Domains\Surveys\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Surveys\Services\SurveyService;
use App\Domains\Surveys\Requests\StoreSurveyAnswersRequest;

class SurveyController extends Controller
{
    public $survey_service;

    public function __construct(SurveyService $survey_service)
    {
        $this->survey_service = $survey_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->survey_service->getAllQuestions();

        return response()->json($response, $response['response_code']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyAnswersRequest $request)
    {
        $response = $this->survey_service->storeSurveyAnswers($request->validated());

        return response()->json($response, $response['response_code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Servey  $servey
     * @return \Illuminate\Http\Response
     */
    public function show($customer_id)
    {
        $response = $this->survey_service->getSurveyAnswersByCustomerId($customer_id);

        return response()->json($response, $response['response_code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Servey  $servey
     * @return \Illuminate\Http\Response
     */
    public function edit(Survey $survey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Servey  $servey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Servey  $servey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey)
    {
        //
    }
}
