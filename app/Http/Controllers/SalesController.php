<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesRequest;
use App\Http\Requests\UpdateSalesRequest;
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
        return view('sales.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalesRequest $request)
    {
        $response = $this->sales_service->createSales($request->validated());

        if($response['response_code'] == 201) {
            return redirect()->route('sales.get')->with('success', 'Sales representative created successfully.');
        }

        return redirect()->back()->withInput()->with('error', $response['response_message']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->sales_service->getById($id);

        if($response['response_code'] != 200) {
            return redirect()->route('sales.get')->with('error', $response['response_message']);
        }

        return view('sales.show', ['sales' => $response['response_data']]);
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
    public function update(UpdateSalesRequest $request, $id)
    {
        $response = $this->sales_service->updateSales($id, $request->validated());

        if($response['response_code'] == 200) {
            return redirect()->route('sales.show', ['id' => $id])->with('success', 'Sales updated successfully.');
        }

        return redirect()->back()->with('error', $response['response_message']);
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
