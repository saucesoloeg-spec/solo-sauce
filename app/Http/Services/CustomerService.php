<?php

namespace App\Http\Services;

use App\Http\Repositories\CustomerRepository;

class CustomerService
{
    private $customer_repository;

    public function __construct(CustomerRepository $customer_repository) 
    {
        $this->customer_repository = $customer_repository;
    }

    public function getAll() 
    {
        $customers = $this->customer_repository->getAll();  
        
        if($customers->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Customers retrieved successfully.',
                'response_data'    => $customers
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No customers found.',
            'response_data'    => null
        ];
    }

}