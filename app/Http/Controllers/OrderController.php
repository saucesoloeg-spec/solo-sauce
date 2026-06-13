<?php

namespace App\Http\Controllers;

use App\Http\Services\OrderService;
use App\Models\Driver;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $order_service;

    public function __construct(OrderService $order_service) 
    {
        $this->order_service = $order_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->order_service->getAll();

        return view('orders.index', ['orders' => $response['response_data']]);
    }

    public function managerIndex()
    {
        $unassignedResp = $this->order_service->getUnassignedOrders();
        $assignedResp = $this->order_service->getAssignedOrders();
        $drivers  = Driver::select('id', 'name', 'phone', 'email')->get();

        $unassigned = $unassignedResp['response_data'] ?? collect();
        $assigned = $assignedResp['response_data'] ?? collect();

        $orders = $unassigned->merge($assigned)
            ->sortByDesc(function ($order) {
                return $order->delivery_date ? strtotime($order->delivery_date) : strtotime($order->created_at);
            })->values();

        return view('orders.manager', [
            'orders'  => $orders,
            'drivers' => $drivers,
        ]);
    }

    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $response = $this->order_service->assignDriver($id, $request->input('driver_id'));

        if ($response['response_code'] === 200) {
            return redirect()->route('manager.orders.get')->with('success', __('orders.driver_assigned_success'));
        }

        return redirect()->route('manager.orders.get')->with('error', __('orders.driver_assignment_failed'));
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
        $response = $this->order_service->getById($id);
        
        if($response['response_code'] == 200) {
            return view('orders.show', ['order' => $response['response_data']]);
        }

        return redirect()->route('orders.get')->with('error', $response['response_message']);
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
}
