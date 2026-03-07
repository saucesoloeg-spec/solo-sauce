<?php

namespace App\Http\Repositories;

use App\Models\Admin;
use App\Models\MonthlyIncome;
use Illuminate\Support\Facades\Hash;

class AdminRepository
{
    private $model;
    private $monthly_income_model;

    public function __construct(Admin $admin, MonthlyIncome $monthlyIncome) 
    {
        $this->model = $admin;
        $this->monthly_income_model = $monthlyIncome;
    }

    public function getAll() 
    {
        return $this->model->all();    
    }
    
    public function store($request) 
    {
        return $this->model->create([
            'name'     => $request['name'],
            'email'    => $request['email'],
            'password' => Hash::make($request['password']),
            'role'     => isset($request['role']) ? $request['role'] : 'admin'
        ]);    
    }

    public function delete($id) 
    {
        return $this->model->where('id', $id)->delete();    
    }

    public function monthlyIncome() 
    {
        $lastMonth = now()->subMonth();
        $endDate   = $lastMonth->copy()->endOfMonth();
        
        return $this->monthly_income_model->where('collect_date', $endDate->format('Y-m-d'))->first();   
    }
    
    public function yearlyIncome() 
    {
        $lastYear  = now()->subYear();
        $startDate = $lastYear->copy()->startOfYear();
        $endDate   = $lastYear->copy()->endOfYear();
        
        return $this->monthly_income_model->whereBetween('collect_date', [$startDate, $endDate])->get();   
    }

}
