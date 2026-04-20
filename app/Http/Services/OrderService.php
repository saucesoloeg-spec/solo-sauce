<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;

class OrderService
{
    private $order_repository;

    public function __construct(OrderRepository $order_repository) 
    {
        $this->order_repository = $order_repository;
    }

    public function getAll() 
    {
        $orders = $this->order_repository->getAll();  
        
        if($orders->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Customers retrieved successfully.',
                'response_data'    => $orders
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No customers found.',
            'response_data'    => null
        ];
    }

    public function getById($id) 
    {
        $orders = $this->order_repository->getById($id);  
        
        if($orders) {
            return [
                'response_code'    => 200,
                'response_message' => 'Customer retrieved successfully.',
                'response_data'    => $orders
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Customer not found.',
            'response_data'    => null
        ];
    }

}