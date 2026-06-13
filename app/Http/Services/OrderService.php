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
                'response_message' => 'Orders retrieved successfully.',
                'response_data'    => $orders
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No orders found.',
            'response_data'    => null
        ];
    }

    public function getById($id) 
    {
        $order = $this->order_repository->getById($id);  
        
        if($order) {
            return [
                'response_code'    => 200,
                'response_message' => 'Order retrieved successfully.',
                'response_data'    => $order
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Order not found.',
            'response_data'    => null
        ];
    }

    public function getUnassignedOrders() 
    {
        $orders = $this->order_repository->getUnassignedOrders();  
        
        if($orders->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Unassigned orders retrieved successfully.',
                'response_data'    => $orders
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No unassigned orders found.',
            'response_data'    => null
        ];
    }

    public function getAssignedOrders()
    {
        $orders = $this->order_repository->getAssignedOrders();

        if ($orders->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Assigned orders retrieved successfully.',
                'response_data'    => $orders
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No assigned orders found.',
            'response_data'    => null
        ];
    }

    public function assignDriver($orderId, $driverId)
    {
        $order = $this->order_repository->assignDriver($orderId, $driverId);

        if ($order) {
            return [
                'response_code'    => 200,
                'response_message' => 'Driver assigned successfully.',
                'response_data'    => $order
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Order not found or could not be updated.',
            'response_data'    => null
        ];
    }

}