<?php

namespace App\Http\Controllers;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateSalesRequest;
use App\Http\Services\DriverService;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public $driver_service;
    public $odoo_service;

    public function __construct(DriverService $driver_service, OdooAuthService $odoo_service) 
    {
        $this->driver_service = $driver_service;
        $this->odoo_service  = $odoo_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->driver_service->getAll();

        return view('drivers.index', ['drivers' => $response['response_data']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        return view('drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDriverRequest $request)
    {
        $response = $this->driver_service->createDriver($request->validated());

        if($response['response_code'] == 201) {
            return redirect()->route('drivers.get')->with('success', 'Driver representative created successfully.');
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
        $response = $this->driver_service->getById($id);
        
        if($response['response_code'] != 200) {
            return redirect()->route('drivers.get')->with('error', $response['response_message']);
        }

        return view('drivers.show', ['driver' => $response['response_data']]);
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
        $response = $this->driver_service->updateSales($id, $request->validated());

        if($response['response_code'] == 200) {
            return redirect()->route('drivers.show', ['id' => $id])->with('success', 'Driver updated successfully.');
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

}
