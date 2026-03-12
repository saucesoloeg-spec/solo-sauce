<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Repositories\AuthRepository;

class AuthService
{
    protected $auth_repository;

    public function __construct(AuthRepository $auth_repository)
    {
        $this->auth_repository = $auth_repository;
    }

    public function register($data)
    {
        $user = $this->auth_repository->register($data);

        if($user) 
        {
            if($data['type'] === 'sales') {
                return [
                    'response_code'    => 201,
                    'response_message' => 'Salesman registered successfully',
                    'response_data'    => $user
                ];
            } elseif ($data['type'] === 'driver') {
                return [
                    'response_code'    => 201,
                    'response_message' => 'Driver registered successfully',
                    'response_data'    => $user
                ];
            }
        }
        
        return [
            'response_code'    => 500,
            'response_message' => 'Registration failed!',
            'response_data'    => null
        ];
    }

    public function login($data)
    {
        $user = $this->auth_repository->login($data);

        if($user) {
            return [
                'response_code'    => 200,
                'response_message' => 'Login successful',
                'response_data'    => $user
            ];
        }

        return [
            'response_code'    => 401,
            'response_message' => 'Invalid credentials',
            'response_data'    => null
        ];

    }

}