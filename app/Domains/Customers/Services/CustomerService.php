<?php

namespace App\Domains\Customers\Services;

use App\Domains\Customers\Repositories\CustomerRepository;
use App\Domains\Odoo\Services\OdooAuthService;

class CustomerService
{
    protected $odoo_service;
    protected $customer_repository;

    public function __construct(CustomerRepository $customer_repository, OdooAuthService $odoo_service)
    {
        $this->odoo_service        = $odoo_service;
        $this->customer_repository = $customer_repository;
    }

    public function getAssignedCustomers()
    {
        $sales = auth()->user();

        $assigned_customers = $this->customer_repository->getAssignedCustomers($sales->id);

        if(!$assigned_customers->isEmpty()) {
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

    public function getCustomersFromDB()
    {
        $sales = auth()->user();

        $customers = $this->customer_repository->getAll();

        if(!$customers->isEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Customers retrieved successfully',
                'response_data'    => $customers
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No customers found',
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

    public function createCustomers(array $data)
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

    public function createCustomer(array $data)
    {
        $sales            = auth()->user();
        $data['sales_id'] = $sales->id;
        $send_odoo = $this->odoo_service->sendCustomerToOdoo($data);
        
        if($send_odoo['response_code'] == 201) {
            $data['id'] = $send_odoo['response_data']['data']['id'];
            $customer   = $this->customer_repository->create($data);
        }
        else {
            $customer = null;
        }

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

    public function updateCustomer($customer_id, array $data)
    {
        $customer = $this->customer_repository->find($customer_id);

        if(!$customer) {
            return [
                'response_code'    => 404,
                'response_message' => 'Customer not found',
                'response_data'    => null
            ];
        }

        $customer->update($data);

        return [
            'response_code'    => 200,
            'response_message' => 'Customer updated successfully',
            'response_data'    => $customer
        ];
    }

    public function checkTodayVisit($sales_id, $customer_id)
    {
        $visit = $this->customer_repository->getTodayVisit($sales_id, $customer_id);

        return [
            'response_code'    => 200,
            'response_message' => 'Visit check retrieved successfully',
            'response_data'    => [
                'has_visit' => $visit ? true : false,
                'visit'     => $visit ? [
                    'id'        => $visit->id,
                    'visit_at'  => $visit->visit_at,
                    'status'    => $visit->status,
                    'notes'     => $visit->notes,
                    'order_id'  => $visit->order_id,
                    'survey'    => $visit->survey,
                ] : null
            ]
        ];
    }
}