<?php

namespace App\Domains\Drivers\Services;

use App\Domains\Drivers\Repositories\DriverRepository;

class DriverService
{
    protected $driver_repository;

    public function __construct(DriverRepository $driver_repository)
    {
        $this->driver_repository = $driver_repository;
    }

    public function home(array $filters = [])
    {
        $driver = auth('drivers')->user();
        $today  = date('Y-m-d');

        $from = !empty($filters['from']) ? date('Y-m-d', strtotime($filters['from'])) : $today;
        $to = !empty($filters['to']) ? date('Y-m-d', strtotime($filters['to'])) : $today;

        $orders = $this->driver_repository->getUpcomingOrdersForDriver($driver->id, $from, $to);

        if ($orders->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Upcoming orders retrieved successfully.',
                'response_data'    => $orders,
            ];
        }

        return [
            'response_code'    => 200,
            'response_message' => 'No upcoming orders found.',
            'response_data'    => [],
        ];
    }
}
