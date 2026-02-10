<?php

namespace App\Domains\Customers\Controllers;

class CustomerService
{
    protected $customer_repository;

    public function __construct(CustomerRepository $customer_repository)
    {
        $this->customer_repository = $customer_repository;
    }

    public function getAssignedCustomers()
    {
        $sales = auth()->user();

        $assigned_customers = $this->customer_repository->getAssignedCustomers($sales->id);

        if($assigned_customers) {
            return [
                'response_code'    => 200,
                'response_message' => 'Assigned customers retrieved successfully',
                'response_data'    => $assigned_customers
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No assigned customers found',
            'response_data'    => null
        ];
    }

    public function getCustomer($id)
    {
        $customer = $this->customer_repository->find($id);

        if($customer) {
            return [
                'response_code'    => 200,
                'response_message' => 'Customer retrieved successfully',
                'response_data'    => $customer
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Customer not found',
            'response_data'    => null
        ];
    }

    public function createCustomer(array $data)
    {
        $customer = $this->customer_repository->create($data);

        if($customer) {
            return [
                'response_code'    => 201,
                'response_message' => 'Customer created successfully',
                'response_data'    => $customer
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to create customer',
            'response_data'    => null
        ];
    }
}