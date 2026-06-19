<?php

namespace App\Domains\Orders\Repositories;

use App\Models\Order;
use Illuminate\Support\Str;
use App\Models\SalesCustomer;
use App\Console\Constants\SystemConstants;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    protected $model;
    protected $sales_customer_model;
    protected $order_status_history;

    public function __construct(Order $model, SalesCustomer $sales_customer_model, OrderStatusHistory $order_status_history)
    {
        $this->model = $model;
        $this->sales_customer_model = $sales_customer_model;
        $this->order_status_history = $order_status_history;
    }

    public function saveOrder(array $data)
    {
        try {
            $orderData = [
                'code'             => $data['code'] ?? $this->generateOrderCode(),
                'sales_id'         => $data['sales_id'] ?? auth('sales')->id(),
                'customer_id'      => $data['customer_id'],
                'customer_name'    => $data['customer_name'] ?? 'Unknown Customer',
                'customer_phone'   => $data['customer_phone'] ?? 'Unknown Phone',
                'delivery_date'    => $data['delivery_date'] ?? now(),
                'amount_total'     => $data['amount_total'],
                'tax'              => $data['tax'] ?? false,
                'amount_tax'       => $data['tax'] ? $data['amount_total'] * SystemConstants::TAX_PERCENTAGE : 0,
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

            $status = $this->order_status_history->create([
                'order_id' => $order->id,
                'status'   => $data['status'] ?? 'pending'
            ]);

            $visit = $this->sales_customer_model->where('customer_id', $order->customer_id)
                ->whereDate('visit_at', date('Y-m-d'))
                ->where('status', 'pending')
                ->first();
            
            if ($visit) {
                $this->sales_customer_model->where('id', $visit->id)->update(['order_id' => $order->id, 'status' => 'completed']);
            }
            else {
                $this->sales_customer_model->create([
                    'sales_id'    => auth('sales')->id(),
                    'customer_id' => $order->customer_id,
                    'visit_at'    => now(),
                    'order_id'    => $order->id,
                    'status'      => 'completed',
                ]);
            }

            return $order;
        } catch (\Exception $exception) {
            Log::error('Error saving order: ' . $exception->getMessage());
            return false;
        }
    }

    protected function generateOrderCode()
    {
        $nextId = (int)$this->model->withTrashed()->max('id') + 1;

        return sprintf('SO%06d', $nextId);
    }

    public function getNewDealsForSales($id, $filters = []) 
    {
        $query = Order::query()
            ->from('orders as o')
            ->select('o.*')
            ->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class)
            ->whereNull('o.deleted_at')
            ->where('o.sales_id', $id)
            ->whereRaw('o.created_at = (
                SELECT MIN(created_at)
                FROM orders
                WHERE customer_id = o.customer_id
            )')
            ->with(['customer', 'products']);

        if (!empty($filters) && isset($filters['from'], $filters['to'])) {
            $query->whereDate('o.created_at', '>=', date("Y-m-d", strtotime($filters['from'])))
                ->whereDate('o.created_at', '<=', date("Y-m-d", strtotime($filters['to'])));
        }

        return $query->get();
    }

    public function getRegularDealsForSales($id, $filters = []) 
    {
        $query = Order::query()
            ->from('orders as o')
            ->select('o.*')
            ->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class)
            ->whereNull('o.deleted_at')
            ->where('o.sales_id', $id)
            ->whereRaw('o.created_at > (
                SELECT MIN(created_at)
                FROM orders
                WHERE customer_id = o.customer_id
            )')
            ->with(['customer', 'products']);

        if (!empty($filters) && isset($filters['from'], $filters['to'])) {
            $query->whereDate('o.created_at', '>=', date("Y-m-d", strtotime($filters['from'])))
                ->whereDate('o.created_at', '<=', date("Y-m-d", strtotime($filters['to'])));
        }

        return $query->get();
    }

    public function getOrderById($id)
    {
        return $this->model->with(['customer', 'products'])->find($id);
    }

    public function updateOrderStatus($data)
    {
        try {
            $order = $this->model->find($data['id']);

            if (!$order) {
                return false;
            }

            $order->delivery_status = $data['status'];
            $order->save();

            $this->order_status_history->create([
                'order_id' => $order->id,
                'status'   => $data['status']
            ]);

            return true;
        } catch (\Exception $exception) {
            Log::error('Error updating order status: ' . $exception->getMessage());
            return false;
        }
    }

    public function updateOrderDelivery($data)
    {
        try {
            $order = $this->model->find($data['id']);

            if (!$order) {
                return [
                    'message' => 'Order not found for id: ' . $data['id'],
                    'success' => false
                ];
            }

            foreach ($data['products'] as $product) {
                $orderProduct = $order->products()->where('product_id', $product['product_id'])->first();

                if ($orderProduct && $orderProduct->quantity >= $product['quantity']) {
                    $order->delivered()->create([
                        'product_id' => $product['product_id'],
                        'quantity'   => $product['quantity']
                    ]);
                }
                else {
                    return [
                        'message' => "Attempted to deliver more than ordered for product_id: {$product['product_id']} in order_id: {$order->id}",
                        'success' => false
                    ];
                }
            }

            return [
                'message' => 'Order delivery updated successfully',
                'success' => true
            ];
        } catch (\Exception $exception) {
            Log::error('Error updating order delivery: ' . $exception->getMessage());
            return [
                'message' => 'Error updating order delivery: ' . $exception->getMessage(),
                'success' => false
            ];
        }

    }

    public function getAllProducts($data)
    {
        $driver = auth('drivers')->user();

        $from = isset($data['from']) ? date("Y-m-d", strtotime($data['from'])) : now();
        $to   = isset($data['to']) ? date("Y-m-d", strtotime($data['to'])) : now();

        $orders = $this->model->where('driver_id', $driver->id)
                              ->whereIn('delivery_status', ['pending', 'accepted', 'delivering', 'delivered'])
                              ->whereDate('delivery_date', '>=', $from)
                              ->whereDate('delivery_date', '<=', $to)
                              ->with(['products', 'delivered'])
                              ->get();

        $products = [];
        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $products[] = [
                    'order_id'   => $order->id,
                    'product_id' => $product->product_id,
                    'quantity'   => $product->quantity - $order->delivered()->where('product_id', $product->product_id)->sum('quantity'),
                ];
            }
        }

        return $products;
    }

}