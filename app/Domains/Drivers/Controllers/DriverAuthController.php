<?php

namespace App\Domains\Drivers\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Domains\Drivers\Services\DriverAuthService;

class DriverAuthController extends Controller
{
    public $driver_auth_service;

    public function __construct(DriverAuthService $driver_auth_service)
    {
        $this->driver_auth_service = $driver_auth_service;
    }

    /**
     * register a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $result = $this->driver_auth_service->register($request);

        return response()->json($result, $result['response_code']);
    }

    /**
     * login a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $result = $this->driver_auth_service->login($request);

        return response()->json($result, $result['response_code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show($driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver  
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $driver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy($driver)
    {
        //
    }
}
