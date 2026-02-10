<?php

namespace App\Domains\Drivers\Services;

use App\Domains\Drivers\Repositories\DriverAuthRepository;

class DriverAuthService
{
    protected $driver_auth_repository;

    public function __construct(DriverAuthRepository $driver_auth_repository)
    {
        $this->driver_auth_repository = $driver_auth_repository;
    }

    public function register($request)
    {
        $driver = $this->driver_auth_repository->register($request);

        if($driver) {
            return [
                'response_code'    => 201,
                'response_message' => 'Driver registered successfully',
                'response_data'    => $driver,
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Driver    registration failed !',
            'response_data'    => null,
        ];
    }

    public function login($request)
    {
        $driver = $this->driver_auth_repository->login($request);

        if($driver) {
            return [
                'response_code'    => 200,
                'response_message' => 'Driver logged in successfully',
                'response_data'    => $driver,
            ];
        }

        return [
            'response_code'    => 401,
            'response_message' => 'Invalid email or password !',
            'response_data'    => null,
        ];
    }
}