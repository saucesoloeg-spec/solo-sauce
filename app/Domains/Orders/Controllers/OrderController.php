<?php

namespace App\Domains\Orders\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Orders\Services\OrderService;
use App\Domains\Odoo\Services\OdooAuthService;
use App\Domains\Orders\Request\GetOrdersRequest;
use App\Domains\Orders\Request\StoreOrderRequest;

class OrderController extends Controller
{
    public $odoo_service;
    public $order_service;

    public function __construct(
        OdooAuthService $odoo_service, 
        OrderService $order_service
    )
    {
        $this->odoo_service  = $odoo_service;
        $this->order_service = $order_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetOrdersRequest $request)
    {
        $orders = $this->odoo_service->getOrders($request->validated());

        return response()->json([
            'response_code'    => 200,
            'response_message' => 'Orders fetched successfully',
            'response_data'    => $orders['data'] ?? []
        ]);
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
    public function store(StoreOrderRequest $request)
    {
        $response = $this->order_service->saveOrder($request->validated());

        return response()->json($response, $response['response_code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order_service->getOrderById($id);
        $odoo_order = $this->odoo_service->getOrderById($order->odoo_id);

        return response()->json([
            'response_code'    => 200,
            'response_message' => 'Order fetched successfully',
            'response_data'    => $odoo_order['data'] ?? []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
