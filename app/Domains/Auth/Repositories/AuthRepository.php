<?php

namespace App\Domains\Auth\Repositories;

use App\Models\Driver;
use App\Models\Sales;

class AuthRepository
{
    private $sales_model;
    private $driver_model;

    public function __construct(Sales $sales_model, Driver $driver_model)
    {
        $this->sales_model = $sales_model;
        $this->driver_model = $driver_model;
    }

    function randomString($length = 5) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public function register($data)
    {
         // Generate a 7 char unique UUID for the user
        if($data['type'] === 'sales') {
            // Create sales user
            $uuid = $this->randomString(7);
            while($this->sales_model->where('uuid', $uuid)->exists()) {
                $uuid = $this->randomString(7);
            }

            $user = $this->sales_model->create([
                'uuid'     => $uuid,
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'],
                'password' => bcrypt($data['password']),
            ]);
        } elseif ($data['type'] === 'driver') {
            // Create driver user
            $uuid = $this->randomString(7);
            while($this->driver_model->where('uuid', $uuid)->exists()) {
                $uuid = $this->randomString(7); // Regenerate if UUID already exists
            }

            $user = $this->driver_model->create([
                'uuid'     => $uuid, 
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'],
                'password' => bcrypt($data['password']),
            ]);
        }

        return $user;
    }

    public function login($data)
    {
        $type = $data['type'] ?? null;
        $user = $this->sales_model->where('uuid', $data['uuid'])->first();
        if(!$user) {
            $user = $this->driver_model->where('uuid', $data['uuid'])->first();
            $type = 'driver';
        } 
        else {
            $type = 'sales';
        }

        if($user && password_verify($data['password'], $user->password)) {
            // Generate token
            $token       = $user->createToken($type.'AuthToken')->plainTextToken;
            $user->token = $token; // Add token to user object for response
            $user->type  = $type;  // Add user type to user object for response

            return $user;
        }

        return null;
    }
}
