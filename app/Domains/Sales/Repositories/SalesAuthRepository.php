<?php

namespace App\Domains\Sales\Repositories;

use App\Models\Sales;
use Illuminate\Support\Facades\Hash;

class SalesAuthRepository
{
    private $sales_model;

    public function __construct(Sales $sales_model)
    {
        $this->sales_model = $sales_model;
    }

    public function register($request)
    {
        return $this->sales_model->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);
    }

    public function login($request)
    {
        $sales = $this->sales_model->where('email', $request->email)->first();

        if($sales && Hash::check($request->password, $sales->password)) {
            if ($request->filled('fcm_token')) {
                $sales->update([
                    'fcm_token' => $request->fcm_token,
                ]);
            }

            $token        = $sales->createToken('salesToken')->plainTextToken;
            $sales->token = $token;
            
            return $sales;
        }

        return null;
    }
}