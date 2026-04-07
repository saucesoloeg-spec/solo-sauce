<?php

namespace App\Domains\Orders\Services;

use App\Domains\Orders\Repositories\OrderRepository;

class OrderService
{
    protected $order_repository;

    public function __construct(OrderRepository $order_repository)
    {
        $this->order_repository = $order_repository;
    }

    public function saveOrder($data)
    {
        $order = $this->order_repository->saveOrder($data);

        if ($order) {
            return [
                'response_code'    => 201,
                'response_message' => 'Order created successfully',
                'response_data'    => $order
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Failed to create order',
            'response_data'    => null
        ];
    }
}
