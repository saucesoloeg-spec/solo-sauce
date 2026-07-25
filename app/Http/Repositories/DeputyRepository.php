<?php

namespace App\Http\Repositories;

use App\Models\Deputy;
use App\Models\Order;
use App\Models\Vehicle;

class DeputyRepository
{
    private $deputy_model;
    private $order_model;
    private $vehicle_model;

    public function __construct(Deputy $deputy_model, Order $order_model, Vehicle $vehicle_model)
    {
        $this->deputy_model = $deputy_model;
        $this->order_model = $order_model;
        $this->vehicle_model = $vehicle_model;
    }

    public function getAll()
    {
        return $this->deputy_model
            ->with(['vehicles.driver', 'orderAssignments.order.customer'])
            ->orderBy('name')
            ->get();
    }

    public function create(array $data)
    {
        return $this->deputy_model->create($data);
    }

    public function getOrdersForAssignment()
    {
        return $this->order_model
            ->with(['customer', 'deputy'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getVehiclesForAssignment()
    {
        return $this->vehicle_model
            ->with(['driver', 'deputy'])
            ->orderByDesc('created_at')
            ->get();
    }
}
