<?php

namespace App\Http\Controllers;

use App\Http\Services\SalesService;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public $sales_service;

    public function __construct(SalesService $sales_service) 
    {
        $this->sales_service = $sales_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->sales_service->getAll();

        return view('sales.index', ['sales' => $response['response_data']]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
     * Display a listing of the schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule()
    {
        $response = $this->sales_service->getSchedule();

        return view('sales.schedule', ['schedules' => $response['response_data']]);
    }

    /**
     * Update visit date for a schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateVisitDate(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:sales_customers,id',
            'visit_date' => 'required|date'
        ]);

        $response = $this->sales_service->updateVisitDate($request->schedule_id, $request->visit_date);

        return response()->json($response, $response['response_code']);
    }

    /**
     * Delete a schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:sales_customers,id'
        ]);

        $response = $this->sales_service->deleteSchedule($request->schedule_id);

        return response()->json($response, $response['response_code']);
    }
}
