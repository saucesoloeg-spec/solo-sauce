<?php

namespace App\Domains\Orders\Repositories;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderRepository
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function saveOrder(array $data)
    {
        try {
            $orderData = [
                'code'             => $data['code'] ?? $this->generateOrderCode(),
                'sales_id'         => $data['sales_id'] ?? null,
                'customer_id'      => $data['customer_id'],
                'customer_name'    => $data['customer_name'] ?? 'Unknown Customer',
                'customer_phone'   => $data['customer_phone'] ?? 'Unknown Phone',
                'delivery_date'    => $data['delivery_date'] ?? now(),
                'amount_total'     => $data['amount_total'],
                'amount_tax'       => $data['amount_tax'] ?? 0,
                'state'            => $data['state'] ?? 'pending',
                'payment_status'   => $data['payment_status'],
                'driver_id'        => $data['driver_id'] ?? null,
                'delivery_status'  => $data['delivery_status'] ?? 'pending',
                'notes'            => $data['notes'] ?? null,
            ];

            $order = $this->model->create($orderData);
            
            if($order && isset($data['products']) && is_array($data['products'])) {
                foreach ($data['products'] as $product) {
                    $order->products()->create([
                        'product_id' => $product['product_id'],
                        'quantity'   => $product['quantity'],
                    ]);
                }
            }

            return $order;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return false;
        }
    }

    protected function generateOrderCode()
    {
        $nextId = (int)$this->model->withTrashed()->max('id') + 1;

        return sprintf('SO%06d', $nextId);
    }
}
