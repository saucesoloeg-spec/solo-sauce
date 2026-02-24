<?php

namespace App\Domains\Customers\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Customers\Services\CustomerService;
use App\Domains\Customers\Requests\CreateCustomerRequest;

class CustomerController extends Controller
{
    public $customer_service;

    public function __construct(CustomerService $customer_service)
    {
        $this->customer_service = $customer_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->customer_service->getAssignedCustomers();

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
    public function store(CreateCustomerRequest $request)
    {
        $response = $this->customer_service->createCustomer($request->validated());

        return response()->json($response, $response['response_code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->customer_service->getCustomer($id);

        return response()->json($response, $response['response_code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CreateCustomerRequest $request, Customer $customer)
    {
        $response = $this->customer_service->updateCustomer($customer->id, $request->validated());

        return response()->json($response, $response['response_code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if($customer->delete()) {
            return response()->json([
                    'response_code'    => 200,
                    'response_message' => 'Customer deleted successfully',
                    'response_data'    => null
            ], 200);
        }

        return response()->json([
            'response_code'    => 500,
            'response_message' => 'Failed to delete customer',
            'response_data'    => null
        ], 500);
    }
}
