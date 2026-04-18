<?php

namespace App\Domains\Sales\Controllers;

use App\Models\sales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Sales\Services\SalesService;

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
        $response = $this->sales_service->dashboard();

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display a listing of the schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule()
    {
        $response = $this->sales_service->getSchedule();

        return response()->json($response, $response['response_code']);
    }

    /**
     * Display a listing of the schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduleHistory()
    {
        $response = $this->sales_service->scheduleHistory();

        return response()->json($response, $response['response_code']);
    }

    /**
     * Cancel a scheduled visit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelSchedule($id)
    {
        $sales = auth('sales')->user();
        $response = $this->sales_service->cancelSchedule($id, $sales->id);

        return response()->json($response, $response['response_code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit(sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(sales $sales)
    {
        //
    }
}
