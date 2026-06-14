<?php

namespace App\Domains\Drivers\Repositories;

use App\Models\Order;

class DriverRepository
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getUpcomingOrdersForDriver(int $driverId, string $from, string $to)
    {
        return $this->order
            ->where('driver_id', $driverId)
            ->whereDate('delivery_date', '>=', $from)
            ->whereDate('delivery_date', '<=', $to)
            ->whereNotIn('delivery_status', ['delivered', 'completed', 'canceled', 'cancelled'])
            ->with(['customer', 'products', 'sales'])
            ->orderBy('delivery_date')
            ->get();
    }

    public function getDashboardDataForDriver(int $driverId, string $from, string $to)
    {
        $totalOrders = $this->order
            ->where('driver_id', $driverId)
            ->whereDate('delivery_date', '>=', $from)
            ->whereDate('delivery_date', '<=', $to)
            ->count();

        $completedOrders = $this->order
            ->where('driver_id', $driverId)
            ->whereDate('delivery_date', '>=', $from)
            ->whereDate('delivery_date', '<=', $to)
            ->whereIn('delivery_status', ['delivered', 'completed'])
            ->count();

        $canceledOrders = $this->order
            ->where('driver_id', $driverId)
            ->whereDate('delivery_date', '>=', $from)
            ->whereDate('delivery_date', '<=', $to)
            ->whereIn('delivery_status', ['canceled', 'cancelled'])
            ->count();

        return [
            'total_orders'     => $totalOrders,
            'completed_orders' => $completedOrders,
            'canceled_orders'  => $canceledOrders,
        ];
    }
}
