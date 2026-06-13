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
}
