<?php

namespace App\Domains\Sales\Controllers;

use App\Models\sales;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Domains\Sales\Services\SalesAuthService;

class SalesAuthController extends Controller
{
    public $sales_auth_service;

    public function __construct(SalesAuthService $sales_auth_service)
    {
        $this->sales_auth_service = $sales_auth_service;
    }

    /**
     * register a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $result = $this->sales_auth_service->register($request);

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
        $result = $this->sales_auth_service->login($request);

        return response()->json($result, $result['response_code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show(sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(sales $sales)
    {
        //
    }
}
