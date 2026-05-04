<?php

namespace App\Http\Services;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\SalesRepository;

class SalesService
{
    private $sales_repository;
    private $customer_repository;
    private $odoo_service;

    public function __construct(
        SalesRepository $sales_repository, 
        CustomerRepository $customer_repository,
        OdooAuthService $odoo_service
    ) 
    {
        $this->sales_repository    = $sales_repository;
        $this->customer_repository = $customer_repository;
        $this->odoo_service = $odoo_service;
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
        $all_sales = $this->sales_repository->getAll();
        
        if($schedules->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule retrieved successfully.',
                'response_data'    => [
                    'schedules' => $schedules,
                    'sales'     => $all_sales
                ]
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No schedule found.',
            'response_data'    => null
        ];
    }

    public function updateVisitDate($scheduleId, $visitDate, $salesId)
    {
        $updated = $this->sales_repository->updateVisitDate($scheduleId, $visitDate, $salesId);

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

    public function getById($id)
    {
        $sales = $this->sales_repository->getById($id);

        if($sales) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales retrieved successfully.',
                'response_data'    => $sales
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Sales not found.',
            'response_data'    => null
        ];
    }

    public function updateSales($id, $data)
    {
        // Only update password if provided
        if(!$data['password']) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $updated = $this->sales_repository->update($id, $data);

        if($updated) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales updated successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to update sales.',
            'response_data'    => null
        ];
    }

    public function createSales($data)
    {
        // Generate UUID
        $data['uuid']     = \Illuminate\Support\Str::uuid();
        $data['password'] = bcrypt($data['password']);

        $authResponse = $this->odoo_service->createOdooAccount($data);

        if($authResponse['success'] == true) {
            $data['odoo_id'] = $authResponse['data']['user']['id'];
            $sales  = $this->sales_repository->create($data);

            // $updated = $this->odoo_service->updateOdooAccount([
            //     'odoo_id'      => $data['odoo_id'],
            //     'city_odoo_id' => $data['city_odoo_id']
            // ]);
            // dd($updated);

            if($sales && $sales->id) {// && $updated['success'] == true
                return [
                    'response_code'    => 201,
                    'response_message' => $updated['error']['detail'] ?? 'Sales created successfully.',
                    'response_data'    => $sales
                ];
            }

            return [
                'response_code'    => 400,
                'response_message' => 'Failed to create sales in Odoo.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to create sales.',
            'response_data'    => null
        ];
    }

    public function getScheduleInfo() 
    {
        $sales     = $this->sales_repository->getAll();
        $customers = $this->customer_repository->getAll();
        
        if($sales && $customers) {
            return [
                'response_code'    => 200,
                'response_message' => 'Data retrieved successfully',
                'response_data'    => [
                    'sales'     => $sales,
                    'customers' => $customers
                ]
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Data retrieved Faild',
            'response_data'    => []
        ];
    }

    public function createSchedule($data) 
    {
        $result = $this->sales_repository->createSchedule($data);

        if($result) {
            return [
                'response_code'    => 200,
                'response_message' => 'Visit date Schedule successfully',
                'response_data'    => []
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Create Visit date Schedule Failed',
            'response_data'    => []
        ];
    }
}