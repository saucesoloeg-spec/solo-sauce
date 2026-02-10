<?php

namespace App\Domains\Drivers\Repositories;

use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class DriverAuthRepository
{
    private $driver_model;

    public function __construct(Driver $driver_model)
    {
        $this->driver_model = $driver_model;
    }

    public function register($request)
    {
        return $this->driver_model->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);
    }

    public function login($request)
    {
        $driver = $this->driver_model->where('email', $request->email)->first();

        if($driver && Hash::check($request->password, $driver->password)) {
            $token        = $driver->createToken('driverToken')->plainTextToken;
            $driver->token = $token;
            
            return $driver;
        }

        return null;
    }
}