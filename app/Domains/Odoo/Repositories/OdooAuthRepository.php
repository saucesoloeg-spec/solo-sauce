<?php

namespace App\Domains\Odoo\Repositories;

use App\Models\Auth;

class OdooAuthRepository
{
    private $model;

    public function __construct(Auth $model)
    {
        $this->model = $model;
    }

    public function getLatestToken($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function create($data)
    {
        return $this->model->updateOrCreate([
            'email'    => $data['email'],
        ], 
        [
            'access_token'  => $data['access_token'] ?? null,
            'refresh_token' => $data['refresh_token'] ?? null,
            'expires_at'    => $data['expires_at'] ?? null,
        ]);
    }
}