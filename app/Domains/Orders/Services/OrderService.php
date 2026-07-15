<?php

namespace App\Domains\Orders\Services;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Domains\Orders\Repositories\OrderRepository;

class OrderService
{
    protected $order_repository;
    protected $odoo_service;

    public function __construct(OrderRepository $order_repository, OdooAuthService $odoo_service)
    {
        $this->order_repository = $order_repository;
        $this->odoo_service = $odoo_service;
    }

    public function saveOrder($data)
    {
        $payload = [
            'customer_id'      => (int)$data['customer_id'], // cast it as integer
            'date_order'       => date('Y-m-d H:i:s'), // current date and time
            'amount_total'     => (float)$data['amount_total'], // cast it as
            'notes'            => $data['notes'],
            'payment_method'   => $data['payment_method'],
            'delivery_date'    => (string)$data['delivery_date'],
            'order_lines'      => $data['products'] ?? [],
        ];

        $send_order = $this->odoo_service->sendOrderToOdoo($payload);
        
        if(isset($send_order['success']) && $send_order['success'] === true) {
                $order = $this->order_repository->saveOrder($data, $send_order['data']['id'] ?? null);
        } else {
            return [
                'response_code'    => 500,
                'response_message' => 'Failed to send order to Odoo, '. $send_order['message'] ?? 'Unknown error',
                'response_data'    => null
            ];
        }

        if(isset($send_order['success']) && $send_order['success'] === true) {
            return [
                'response_code'    => 201,
                'response_message' => 'Order created successfully',
                'response_data'    => $order
            ];
        }
        
        return [
            'response_code'    => 500,
            'response_message' => 'Failed to send order to Odoo, '. $send_order['message'] ?? 'Unknown error',
            'response_data'    => null
        ];
    }

    public function getOrderById($id)
    {
        return $this->order_repository->getOrderById($id);
    }

    public function updateOrderStatus($data)
    {
        $updated = $this->order_repository->updateOrderStatus($data);

        if ($updated) {
            return [
                'response_code'    => 200,
                'response_message' => 'Order status updated successfully',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Failed to update order status',
            'response_data'    => null
        ];
    }

    public function updateOrderDelivery($data)
    {
        $updated = $this->order_repository->updateOrderDelivery($data);

        if ($updated['success'] === true) {
            return [
                'response_code'    => 200,
                'response_message' => 'Order delivery updated successfully',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => $updated['message'] ?? 'Failed to update order delivery',
            'response_data'    => null
        ];
    }

    public function getAllProducts($request)
    {
        $inventory = $this->order_repository->getAllProducts($request);
        
        if ($inventory) {
            return [
                'response_code'    => 200,
                'response_message' => 'Products fetched successfully',
                'response_data'    => $inventory
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Failed to fetch products',
            'response_data'    => null
        ];
    }

}
