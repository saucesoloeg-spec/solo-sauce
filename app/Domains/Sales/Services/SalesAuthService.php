<?php

namespace App\Domains\Sales\Services;

use App\Domains\Sales\Repositories\SalesAuthRepository;

class SalesAuthService
{
    protected $sales_auth_repository;

    public function __construct(SalesAuthRepository $sales_auth_repository)
    {
        $this->sales_auth_repository = $sales_auth_repository;
    }

    public function register($request)
    {
        $sales = $this->sales_auth_repository->register($request);

        if($sales) {
            return [
                'response_code'    => 201,
                'response_message' => 'Sales registered successfully',
                'response_data'    => $sales,
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Sales registration failed !',
            'response_data'    => null,
        ];
    }

    public function login($request)
    {
        $sales = $this->sales_auth_repository->login($request);

        if($sales) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales logged in successfully',
                'response_data'    => $sales,
            ];
        }

        return [
            'response_code'    => 401,
            'response_message' => 'Invalid email or password !',
            'response_data'    => null,
        ];
    }

    public function logout($request)
    {
        $logout = auth('sales')->logout();

        if($logout) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales logged out successfully',
                'response_data'    => null,
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Sales logout failed !',
            'response_data'    => null,
        ];
    }
}