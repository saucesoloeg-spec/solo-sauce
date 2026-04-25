<?php

namespace App\Domains\Sales\Services;

use App\Domains\Customers\Repositories\CustomerRepository;
use App\Domains\Customers\Services\CustomerService;
use App\Domains\Orders\Repositories\OrderRepository;
use App\Domains\Sales\Repositories\SalesRepository;
use App\Domains\Surveys\Repositories\SurveyRepository;

class SalesService
{
    protected $sales_repository;
    protected $order_repository;
    protected $survey_repository;
    protected $customer_repository;

    public function __construct(
        SalesRepository $sales_repository, 
        OrderRepository $order_repository, 
        SurveyRepository $survey_repository,
        CustomerRepository $customer_repository
    )
    {
        $this->sales_repository    = $sales_repository;
        $this->order_repository    = $order_repository;
        $this->survey_repository   = $survey_repository;
        $this->customer_repository = $customer_repository;
    }

    public function dashboard($data) 
    {
        $sales = auth('sales')->user();

        $visits = $this->sales_repository->getAllBySalesId($sales->id, $data);
        
        $new_deals = $this->order_repository->getNewDealsForSales($sales->id, $data);
        $regular_deals = $this->order_repository->getRegularDealsForSales($sales->id, $data);

        $response = [
            'total_visits'     => $visits->count(),
            'today_visits'     => $visits->where('visit_at', date('Y-m-d'))->count(),
            'upcoming_visits'  => $visits->where('visit_at', '>', date('Y-m-d'))->count(),
            'past_visits'      => $visits->where('visit_at', '<', date('Y-m-d'))->where('status', 'completed')->count(),
            'cancelled_visits' => $visits->where('visit_at', '<', date('Y-m-d'))->where('status', 'cancelled')->count(),
            'new_deals'        => $new_deals->count(),
            'regular_deal'     => $regular_deals->count()
        ];

        if($visits->isNotEmpty() || $new_deals->isNotEmpty() || $regular_deals->isNotEmpty()) {
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

    public function getAll($customer_id = null) 
    {
        $customer = null;

        if($customer_id) {
            $customer = $this->customer_repository->find($customer_id);
        }

        $sales = $this->sales_repository->getAllSales($customer?->city_odoo_id);  
        
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

    public function getSchedule($request) 
    {
        $sales         = auth('sales')->user();
        
        $schedules     = $this->sales_repository->getSchedule($sales->id, $request);
        $new_deals     = $this->order_repository->getNewDealsForSales($sales->id, $request);
        $regular_deals = $this->order_repository->getRegularDealsForSales($sales->id, $request);
        $surveys       = $this->survey_repository->getAnswersBySalesId($sales->id); 
        
        if($schedules->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule retrieved successfully.',
                'response_data'    => [
                    'visits'         => $schedules,
                    'new_deals'      => $new_deals,
                    'regular_deals'  => $regular_deals,
                    'surveys'        => $surveys
                ]
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No schedule found.',
            'response_data'    => null
        ];
    }

    public function scheduleHistory($request) 
    {
        $sales         = auth('sales')->user();
        $schedules     = $this->sales_repository->getPastSchedule($sales->id, $request);
        $new_deals     = $this->order_repository->getNewDealsForSales($sales->id, $request);
        $regular_deals = $this->order_repository->getRegularDealsForSales($sales->id, $request);
        $surveys       = $this->survey_repository->getAnswersBySalesId($sales->id); 
        
        if($schedules->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule history retrieved successfully.',
                'response_data'    => [
                    'visits'         => $schedules,
                    'new_deals'      => $new_deals,
                    'regular_deals'  => $regular_deals,
                    'surveys'        => $surveys
                ]
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