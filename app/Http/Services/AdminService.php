<?php

namespace App\Http\Services;

use App\Http\Repositories\AdminRepository;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    private $admin_repository;

    public function __construct(AdminRepository $admin_repository) {
        $this->admin_repository = $admin_repository;
    }

    public function save($request) 
    {
        return $this->admin_repository->store($request);
    }

    public function loggedinAdmin() 
    {
        return Auth::guard('admin')->user();    
    }

    public function getPastMonthlyIncome() 
    {
        return $this->admin_repository->PastMonthlyIncome();
    }

    public function getMonthlyIncome() 
    {
        return $this->admin_repository->monthlyIncome();
    }

    public function getMonthlyOrders() 
    {
        return $this->admin_repository->monthlyOrders();
    }

    public function getYearlyIncome() 
    {
        return $this->admin_repository->yearlyIncome();
    }

    public function getTopNewDealsByRepresentative()
    {
        return $this->admin_repository->topNewDealsByRepresentative();
    }

    public function getTopReordersByRepresentative()
    {
        return $this->admin_repository->topReordersByRepresentative();
    }

    public function getTopSurveyAnswersByRepresentative()
    {
        return $this->admin_repository->topSurveyAnswersByRepresentative();
    }

    public function get_all() 
    {
        return $this->admin_repository->getAll();    
    }

    public function store($request) 
    {
        try {
            $admin = $this->admin_repository->store($request);

            return [
                'code'    => 200,
                'message' => 'Admin created successfully.',
                'admin'   => $admin
            ];

        } catch (\Exception $e) {
            // Handle unexpected errors
            return [
                'code'    => 500,
                'message' => 'An error occurred while creating the admin.',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function delete($request) 
    {
        return $this->admin_repository->delete($request['admin_id']);
    }

}
