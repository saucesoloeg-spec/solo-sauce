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
        $order = $this->order_repository->saveOrder($data);

        if ($order) {
            $payload = [
                'customer_id'      => (int)$order->customer_id, // cast it as integer
                'date_order'       => (string)$order->created_at,
                'notes'            => $order->notes,
                'payment_method'   => $order->payment_method,
                'delivery_date'    => (string)$order->delivery_date,
                'order_lines'      => $order->products->map(function($product) {
                    return [
                        'product_id' => $product->product_id,
                        'quantity'   => $product->quantity,
                        'discount'   => $data['discount'] ?? 0,
                    ];
                })->toArray(),
            ];
            $send_order = $this->odoo_service->sendOrderToOdoo($payload);

            $order->update(['odoo_id' => $send_order['data']['id'] ?? null]);
            
            if(isset($send_order['success']) && $send_order['success'] === true) {
                return [
                    'response_code'    => 201,
                    'response_message' => 'Order created successfully',
                    'response_data'    => $order
                ];
            }
            
            return [
                'response_code'    => 500,
                'response_message' => 'Failed to send order to Odoo',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Failed to create order',
            'response_data'    => null
        ];
    }

    public function getOrderById($id)
    {
        return $this->order_repository->getOrderById($id);
    }

}
