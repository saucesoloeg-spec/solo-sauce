<?php

namespace App\Http\Services;

use App\Http\Repositories\VehicleRepository;

class VehicleService
{
    private $vehicle_repository;

    public function __construct(VehicleRepository $vehicle_repository)
    {
        $this->vehicle_repository = $vehicle_repository;
    }

    public function getAll()
    {
        $vehicles = $this->vehicle_repository->getAll();

        if ($vehicles->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Vehicles retrieved successfully.',
                'response_data'    => $vehicles,
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No vehicles found.',
            'response_data'    => null,
        ];
    }

    public function getById($id)
    {
        $vehicle = $this->vehicle_repository->getById($id);

        if ($vehicle) {
            return [
                'response_code'    => 200,
                'response_message' => 'Vehicle retrieved successfully.',
                'response_data'    => $vehicle,
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Vehicle not found.',
            'response_data'    => null,
        ];
    }

    public function createVehicle($data)
    {
        $vehicle = $this->vehicle_repository->create($data);

        if ($vehicle && $vehicle->id) {
            return [
                'response_code'    => 201,
                'response_message' => 'Vehicle created successfully.',
                'response_data'    => $vehicle,
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to create vehicle.',
            'response_data'    => null,
        ];
    }

    public function updateVehicle($id, $data)
    {
        $updated = $this->vehicle_repository->update($id, $data);

        if ($updated) {
            return [
                'response_code'    => 200,
                'response_message' => 'Vehicle updated successfully.',
                'response_data'    => null,
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to update vehicle.',
            'response_data'    => null,
        ];
    }

    public function deleteVehicle($id)
    {
        $deleted = $this->vehicle_repository->delete($id);

        if ($deleted) {
            return [
                'response_code'    => 200,
                'response_message' => 'Vehicle deleted successfully.',
                'response_data'    => null,
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Vehicle not found.',
            'response_data'    => null,
        ];
    }

    public function assignDriver($vehicleId, $driverId)
    {
        $vehicle = $this->vehicle_repository->assignDriver($vehicleId, $driverId);

        if ($vehicle) {
            return [
                'response_code'    => 200,
                'response_message' => 'Driver assigned to vehicle successfully.',
                'response_data'    => $vehicle,
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Vehicle not found.',
            'response_data'    => null,
        ];
    }

    public function unassignDriver($vehicleId)
    {
        $vehicle = $this->vehicle_repository->unassignDriver($vehicleId);

        if ($vehicle) {
            return [
                'response_code'    => 200,
                'response_message' => 'Driver unassigned from vehicle successfully.',
                'response_data'    => $vehicle,
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Vehicle not found.',
            'response_data'    => null,
        ];
    }
}
