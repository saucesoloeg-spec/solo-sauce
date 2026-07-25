<?php

namespace App\Http\Repositories;

use App\Models\Order;
use App\Models\DeputyOrderAssignment;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    private $model;

    public function __construct(Order $order) 
    {
        $this->model = $order;
    }

    public function getAll() 
    {
        return $this->model->with(['customer', 'sales', 'products', 'deputy'])->get();    
    }

    public function getById($id) 
    {
        return $this->model->with(['customer', 'sales', 'products', 'delivered', 'deputy'])->find($id);    
    }

    public function getUnassignedOrders()
    {
        return $this->model->with(['customer', 'sales', 'products', 'deputy'])->whereNull('driver_id')->get();
    }

    public function getAssignedOrders()
    {
        return $this->model->with(['customer', 'sales', 'products', 'driver', 'deputy'])->whereNotNull('driver_id')->get();
    }

    public function assignDriver($orderId, $driverId, $driverOrderRank = null)
    {
        $order = $this->model->find($orderId);

        if (!$order) {
            return null;
        }

        $currentDriverId = $order->driver_id;
        $currentRank = $order->driver_order_rank;

        DB::transaction(function () use ($order, $driverId, $driverOrderRank, $currentDriverId, $currentRank) {
            $targetRank = $driverOrderRank !== null ? (int) $driverOrderRank : null;

            if ($targetRank !== null && $targetRank < 1) {
                $targetRank = 1;
            }

            $isSameDriver = (int) $currentDriverId === (int) $driverId;

            if ($isSameDriver && $currentRank !== null && $targetRank !== null) {
                if ($targetRank < $currentRank) {
                    $this->model
                        ->where('driver_id', $driverId)
                        ->where('id', '!=', $order->id)
                        ->where('driver_order_rank', '>=', $targetRank)
                        ->where('driver_order_rank', '<', $currentRank)
                        ->increment('driver_order_rank');
                } elseif ($targetRank > $currentRank) {
                    $this->model
                        ->where('driver_id', $driverId)
                        ->where('id', '!=', $order->id)
                        ->where('driver_order_rank', '<=', $targetRank)
                        ->where('driver_order_rank', '>', $currentRank)
                        ->decrement('driver_order_rank');
                }
            } elseif ($targetRank !== null) {
                $this->model
                    ->where('driver_id', $driverId)
                    ->where('id', '!=', $order->id)
                    ->where('driver_order_rank', '>=', $targetRank)
                    ->increment('driver_order_rank');
            }

            if (!$isSameDriver && $currentDriverId !== null && $currentRank !== null) {
                $this->model
                    ->where('driver_id', $currentDriverId)
                    ->where('id', '!=', $order->id)
                    ->where('driver_order_rank', '>', $currentRank)
                    ->decrement('driver_order_rank');
            }

            if ($targetRank === null) {
                $maxRank = $this->model
                    ->where('driver_id', $driverId)
                    ->where('id', '!=', $order->id)
                    ->max('driver_order_rank');

                $targetRank = $maxRank ? ($maxRank + 1) : 1;
            }

            $order->driver_id = $driverId;
            $order->driver_order_rank = $targetRank;
            $order->delivery_status = 'Assigned';
            $order->save();
        });

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

    public function assignDeputy($orderId, $deputyId)
    {
        $order = $this->model->find($orderId);

        if (!$order) {
            return null;
        }

        $order->deputy_id = $deputyId;
        $order->save();

        try {
            DeputyOrderAssignment::create([
                'deputy_id'   => $deputyId,
                'order_id'    => $order->id,
                'assigned_at' => now(),
            ]);
        } catch (\Exception $exception) {
            // Keep the assignment successful even if the history record fails.
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