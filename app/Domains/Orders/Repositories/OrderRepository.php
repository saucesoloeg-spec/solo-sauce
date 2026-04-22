<?php

namespace App\Domains\Orders\Repositories;

use App\Models\Order;
use Illuminate\Support\Str;
use App\Models\SalesCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    protected $model;
    protected $sales_customer_model;

    public function __construct(Order $model, SalesCustomer $sales_customer_model)
    {
        $this->model = $model;
        $this->sales_customer_model = $sales_customer_model;
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
        $query = DB::table('orders as o')
                ->where('o.sales_id', $id)
                ->whereRaw('o.created_at = (
                    SELECT MIN(created_at)
                    FROM orders
                    WHERE customer_id = o.customer_id
                )');

        if(!empty($filters) && (isset($filters['from']) && isset($filters['to']))) {
            $query->whereDate('created_at', '>=', $filters['from'])
                  ->whereDate('created_at', '<=', $filters['to']);
        }
                
        return $query->get();
    }

    public function getRegularDealsForSales($id, $filters = []) 
    {
        $query = DB::table('orders as o')
                 ->where('o.sales_id', $id)
                 ->whereRaw('o.created_at > (
                     SELECT MIN(created_at)
                     FROM orders
                     WHERE customer_id = o.customer_id
                 )');

        if(!empty($filters) && (isset($filters['from']) && isset($filters['to']))) {
            $query->whereDate('created_at', '>=', $filters['from'])
                  ->whereDate('created_at', '<=', $filters['to']);
        }
                
        return $query->get();
    }

}
