<?php

namespace App\Http\Repositories;

use App\Models\Order;
use App\Models\OrderStatusHistory;

class OrderRepository
{
    private $model;

    public function __construct(Order $order) 
    {
        $this->model = $order;
    }

    public function getAll() 
    {
        return $this->model->with(['customer', 'sales', 'products'])->get();    
    }

    public function getById($id) 
    {
        return $this->model->with(['customer', 'sales', 'products', 'delivered'])->find($id);    
    }

    public function getUnassignedOrders()
    {
        return $this->model->with(['customer', 'sales', 'products'])->whereNull('driver_id')->get();
    }

    public function getAssignedOrders()
    {
        return $this->model->with(['customer', 'sales', 'products', 'driver'])->whereNotNull('driver_id')->get();
    }

    public function assignDriver($orderId, $driverId)
    {
        $order = $this->model->find($orderId);

        if (!$order) {
            return null;
        }

        $order->driver_id = $driverId;
        $order->delivery_status = 'Assigned';
        $order->save();

        // record status history
        try {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status'   => 'Assigned'
            ]);
        } catch (\Exception $e) {
            // don't break assignment if history save fails; log if needed
        }

        return $order;
    }

    public function cancelByManager($orderId, $managerName)
    {
        $order = $this->model->find($orderId);

        if (!$order) {
            return null;
        }

        $noteLine = 'Cancelled by fleet manager: ' . $managerName;
        $order->notes = $this->appendNote($order->notes, $noteLine);
        $order->state = 'cancelled';
        $order->save();

        try {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status'   => 'cancelled'
            ]);
        } catch (\Exception $e) {
            // Keep cancel operation successful even if history creation fails.
        }

        return $order;
    }

    public function reactivateByManager($orderId, $managerName)
    {
        $order = $this->model->find($orderId);

        if (!$order) {
            return null;
        }

        $noteLine = 'Reactivated by fleet manager: ' . $managerName;
        $order->notes = $this->appendNote($order->notes, $noteLine);
        $order->state = 'pending';
        $order->save();

        try {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status'   => 'pending'
            ]);
        } catch (\Exception $e) {
            // Keep reactivation operation successful even if history creation fails.
        }

        return $order;
    }

    private function appendNote($existingNotes, $line)
    {
        $existing = trim((string) $existingNotes);

        return $existing !== '' ? $existing . PHP_EOL . $line : $line;
    }

}