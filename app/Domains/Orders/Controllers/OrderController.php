<?php

namespace App\Domains\Orders\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Orders\Services\OrderService;
use App\Domains\Odoo\Services\OdooAuthService;
use App\Domains\Orders\Request\StoreOrderRequest;

class OrderController extends Controller
{
    public $odoo_service;
    public $order_service;

    public function __construct(OdooAuthService $odoo_service, OrderService $order_service)
    {
        $this->odoo_service  = $odoo_service;
        $this->order_service = $order_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $orders = $this->odoo_service->getOrders();

        // returning pesudo data for now
        $orders = [
            [
                "id"              => 1,
                "name"            => "SO00123",
                "customer_id"     => 45,
                "customer_name"   => "Mohamed Ali Store",
                "customer_phone"  => "+20111223344",
                "date_order"      => "2024-02-09T09:00:00Z",
                "amount_total"    => 5500.00,
                "amount_tax"      => 500.00,
                "state"           => "sale",
                "payment_status"  => "partial",
                "delivery_status" => "pending",
                "notes"           => "Urgent delivery"
            ],
            [
                "id"              => 2,
                "name"            => "SO00124",
                "customer_id"     => 46,
                "customer_name"   => "Jhon Doe Store",
                "customer_phone"  => "+20101231231",
                "date_order"      => "2024-06-10T10:00:00Z",
                "amount_total"    => 7500.00,
                "amount_tax"      => 750.00,
                "state"           => "sale",
                "payment_status"  => "paid",
                "delivery_status" => "delivered",
                "notes"           => ""
            ],
            [
                "id"              => 3,
                "name"            => "SO00125",
                "customer_id"     => 47,
                "customer_name"   => "Jane Smith Store",
                "customer_phone"  => "+20109876543",
                "date_order"      => "2024-06-11T11:00:00Z",
                "amount_total"    => 6500.00,
                "amount_tax"      => 650.00,
                "state"           => "sale",
                "payment_status"  => "pending",
                "delivery_status" => "pending",
                "notes"           => "Include gift wrap"
            ]
        ];

        return response()->json([
            'response_code'    => 200,
            'response_message' => 'Orders fetched successfully',
            'response_data'    => $orders
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
        // $order = $this->odoo_service->getOrderById($id);

        // returning pesudo data for now
        $order = [
            "id"              => 1,
            "name"            => "SO00123",
            "customer_id"     => 45,
            "customer_name"   => "Mohamed Ali Store",
            "customer_phone"  => "+20111223344",
            "date_order"      => "2024-02-09T09:00:00Z",
            "amount_total"    => 5500.00,
            "amount_tax"      => 500.00,
            "state"           => "sale",
            "payment_status"  => "partial",
            "delivery_status" => "pending",
            "notes"           => "Urgent delivery"
        ];

        return response()->json([
            'response_code'    => 200,
            'response_message' => 'Order fetched successfully',
            'response_data'    => $order
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
