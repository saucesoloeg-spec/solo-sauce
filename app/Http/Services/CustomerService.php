<?php

namespace App\Http\Services;

use App\Http\Repositories\CustomerRepository;
use App\Domains\Odoo\Services\OdooAuthService;
use App\Models\Order;

class CustomerService
{
    private $customer_repository;
    private $odoo_service;

    public function __construct(CustomerRepository $customer_repository, OdooAuthService $odoo_service) 
    {
        $this->customer_repository = $customer_repository;
        $this->odoo_service        = $odoo_service;
    }

    public function getAll(array $filters = []) 
    {
        // $customers = $this->customer_repository->getAll();

        $result    = $this->odoo_service->getCustomers(array_filter(array_merge([
            'page'  => 1,
            'limit' => 10,
        ], $filters), function ($value) {
            return $value !== null && $value !== '';
        }));
        $customers = collect($result['data']['customers'] ?? []);

        if($customers->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Customers retrieved successfully.',
                'response_data'    => [
                    'customers'  => $customers,
                    'pagination' => $result['data']['pagination'] ?? [],
                ]
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No customers found.',
            'response_data'    => []
        ];
    }

    public function getById($id) 
    {
        // $customer = $this->customer_repository->getById($id);
        // $orders   = $customer->orders()->paginate(10);

        $result   = $this->odoo_service->getCustomerById($id);
        $customer = $result['data'] ?? null;
        $orders   = Order::where('customer_id', $id)->paginate(10);
        
        if($customer) {
            return [
                'response_code'    => 200,
                'response_message' => 'Customer retrieved successfully.',
            'response_data'    => ['customer' => (object) $customer, 'orders' => $orders]
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Customer not found.',
            'response_data'    => null
        ];
    }

}