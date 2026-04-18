<?php

namespace App\Domains\Sales\Services;

use App\Domains\Sales\Repositories\SalesRepository;

class SalesService
{
    protected $sales_repository;

    public function __construct(SalesRepository $sales_repository)
    {
        $this->sales_repository = $sales_repository;
    }

    public function dashboard() 
    {
        $sales = auth('sales')->user();

        $visits = $this->sales_repository->getAllBySalesId($sales->id);

        $response = [
            'total_visits'     => $visits->count(),
            'today_visits'     => $visits->where('visit_at', date('Y-m-d'))->count(),
            'upcoming_visits'  => $visits->where('visit_at', '>', date('Y-m-d'))->count(),
            'past_visits'      => $visits->where('visit_at', '<', date('Y-m-d'))->where('status', 'completed')->count(),
            'cancelled_visits' => $visits->where('visit_at', '<', date('Y-m-d'))->where('status', 'cancelled')->count(),
        ];

        if($visits->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Dashboard data retrieved successfully.',
                'response_data'    => $response
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No dashboard data found.',
            'response_data'    => null
        ];
    }

    public function getAll() 
    {
        $sales = $this->sales_repository->getAll();  
        
        if($sales->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales retrieved successfully.',
                'response_data'    => $sales
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No customers found.',
            'response_data'    => null
        ];
    }

    public function getSchedule() 
    {
        $sales     = auth('sales')->user();

        $schedules = $this->sales_repository->getSchedule($sales->id);  
        
        if($schedules->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule retrieved successfully.',
                'response_data'    => $schedules
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No schedule found.',
            'response_data'    => null
        ];
    }

    public function scheduleHistory() 
    {
        $sales     = auth('sales')->user();
        $schedules = $this->sales_repository->getPastSchedule($sales->id);  
        
        if($schedules->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule history retrieved successfully.',
                'response_data'    => $schedules
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No schedule history found.',
            'response_data'    => null
        ];
    }

    public function cancelSchedule($scheduleId, $salesId)
    {
        $cancelled = $this->sales_repository->cancelSchedule($scheduleId, $salesId);

        if ($cancelled) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule cancelled successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Schedule not found or not belonging to this sales user.',
            'response_data'    => null
        ];
    }
}