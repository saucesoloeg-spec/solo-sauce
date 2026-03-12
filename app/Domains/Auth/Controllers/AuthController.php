<?php

namespace App\Domains\Auth\Controllers;

use App\Domains\Auth\Requests\AuthLoginRequest;
use App\Domains\Auth\Requests\AuthRegisterRequest;
use App\Domains\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $auth_service;

    public function __construct(AuthService $auth_service)
    {
        $this->auth_service = $auth_service;
    }

    /**
    * Handle user login (sales/driver).
    *
    * @return \Illuminate\Http\Response
    */
    public function login(AuthLoginRequest $request)
    {
        $response = $this->auth_service->login($request->validated());

        return response()->json($response);
    }

    /**
    * Handle user registration (sales/driver).
    *
    * @return \Illuminate\Http\Response
    */
    public function register(AuthRegisterRequest $request)
    {
        $response = $this->auth_service->register($request->validated());

        return response()->json($response);
    }

    
}
