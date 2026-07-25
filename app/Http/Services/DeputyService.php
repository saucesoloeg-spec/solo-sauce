<?php

namespace App\Http\Services;

use App\Http\Repositories\DeputyRepository;

class DeputyService
{
    private $deputy_repository;

    public function __construct(DeputyRepository $deputy_repository)
    {
        $this->deputy_repository = $deputy_repository;
    }

    public function getAll()
    {
        $deputies = $this->deputy_repository->getAll();

        return [
            'response_code' => 200,
            'response_message' => $deputies->isNotEmpty() ? 'Deputies retrieved successfully.' : 'No deputies found.',
            'response_data' => $deputies,
        ];
    }

    public function createDeputy(array $data)
    {
        $deputy = $this->deputy_repository->create($data);

        if ($deputy && $deputy->id) {
            return [
                'response_code' => 201,
                'response_message' => 'Deputy created successfully.',
                'response_data' => $deputy,
            ];
        }

        return [
            'response_code' => 400,
            'response_message' => 'Failed to create deputy.',
            'response_data' => null,
        ];
    }

    public function getOrdersForAssignment()
    {
        return $this->deputy_repository->getOrdersForAssignment();
    }

    public function getVehiclesForAssignment()
    {
        return $this->deputy_repository->getVehiclesForAssignment();
    }
}
