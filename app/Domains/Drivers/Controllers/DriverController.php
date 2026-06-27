<?php

namespace App\Domains\Drivers\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Drivers\Request\GetDriverHomeRequest;
use App\Domains\Drivers\Services\DriverService;

class DriverController extends Controller
{
    protected $driver_service;

    public function __construct(DriverService $driver_service)
    {
        $this->driver_service = $driver_service;
    }

    public function home(GetDriverHomeRequest $request)
    {
        $response = $this->driver_service->home($request->validated());

        return response()->json($response, $response['response_code']);
    }

    public function dashboard(GetDriverHomeRequest $request)
    {
        $response = $this->driver_service->dashboard($request->validated());

        return response()->json($response, $response['response_code']);
    }

    public function profile(GetDriverHomeRequest $request)
    {
        $response = $this->driver_service->profile($request->validated());

        return response()->json($response, $response['response_code']);
    }
}
