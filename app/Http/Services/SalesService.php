<?php

namespace App\Http\Services;

use App\Http\Repositories\SalesRepository;

class SalesService
{
    private $sales_repository;

    public function __construct(SalesRepository $sales_repository) 
    {
        $this->sales_repository = $sales_repository;
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
        $schedules = $this->sales_repository->getSchedule();  
        
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

    public function updateVisitDate($scheduleId, $visitDate)
    {
        $updated = $this->sales_repository->updateVisitDate($scheduleId, $visitDate);

        if($updated) {
            return [
                'response_code'    => 200,
                'response_message' => 'Visit date updated successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to update visit date.',
            'response_data'    => null
        ];
    }

    public function deleteSchedule($scheduleId)
    {
        $deleted = $this->sales_repository->deleteSchedule($scheduleId);

        if($deleted) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule deleted successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to delete schedule.',
            'response_data'    => null
        ];
    }
}