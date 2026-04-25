<?php

namespace App\Domains\Sales\Repositories;

use App\Models\Sales;
use App\Models\SalesCustomer;
use Illuminate\Support\Facades\Hash;

class SalesRepository
{
    private $model;
    private $sales_customer_model;

    public function __construct(Sales $sales, SalesCustomer $sales_customer_model)
    {
        $this->model = $sales;
        $this->sales_customer_model = $sales_customer_model;
    }

    public function getAllSales($city_id = null) 
    {
        if($city_id)
            return $this->model->where('city_odoo_id', $city_id)->get();

        return $this->model->all();    
    }

    public function getAllBySalesId($id, $filters = []) 
    {
        $query = $this->sales_customer_model->where('sales_id', $id);
        
        if(!empty($filters) && (isset($filters['from']) && isset($filters['to']))) {
            $query->whereDate('visit_at', '>=', date("Y-m-d", strtotime($filters['from'])))
                  ->whereDate('visit_at', '<=', date("Y-m-d", strtotime($filters['to'])));
        }
        
        return $query->with('order')->get();    
    }

    public function getAll() 
    {
        return $this->sales_customer_model->where->with('sales', 'customer')->get();    
    }

    public function getSchedule($id, $filters = []) 
    {
        $query = $this->sales_customer_model->where('sales_id', $id)->where('visit_at', '>=', now())->with(['order', 'customer']);
        
        if(!empty($filters) && (isset($filters['from']) && isset($filters['to']))) {
            $query->whereDate('visit_at', '>=', date("Y-m-d", strtotime($filters['from'])))
                  ->whereDate('visit_at', '<=', date("Y-m-d", strtotime($filters['to'])));
        }
        
        return $query->get();
    }

    public function getPastSchedule($id, $filters = [])
    {
        $query = $this->sales_customer_model->where('sales_id', $id)
                                            ->where('visit_at', '<', now());

        if(!empty($filters) && (isset($filters['from']) && isset($filters['to']))) {
            $query->whereDate('visit_at', '>=', date("Y-m-d", strtotime($filters['from'])))
                  ->whereDate('visit_at', '<=', date("Y-m-d", strtotime($filters['to'])));
        }

        return $query->with('order')->get();
    }

    public function cancelSchedule($scheduleId, $salesId)
    {
        return $this->sales_customer_model
            ->where('id', $scheduleId)
            ->where('sales_id', $salesId)
            ->where('visit_at', '>=', now())
            ->update(['status' => 'cancelled']);
    }

}