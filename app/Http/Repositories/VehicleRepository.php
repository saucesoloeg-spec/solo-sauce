<?php

namespace App\Http\Repositories;

use App\Models\Vehicle;

class VehicleRepository
{
    private $model;

    public function __construct(Vehicle $vehicle)
    {
        $this->model = $vehicle;
    }

    public function getAll()
    {
        return $this->model->with('driver')->get();
    }

    public function getById($id)
    {
        return $this->model->with('driver')->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function assignDriver($vehicleId, $driverId)
    {
        $vehicle = $this->model->find($vehicleId);

        if (!$vehicle) {
            return null;
        }

        $vehicle->driver_id = $driverId;
        $vehicle->save();

        return $vehicle;
    }

    public function unassignDriver($vehicleId)
    {
        $vehicle = $this->model->find($vehicleId);

        if (!$vehicle) {
            return null;
        }

        $vehicle->driver_id = null;
        $vehicle->save();

        return $vehicle;
    }
}
