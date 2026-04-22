<?php

namespace App\Domains\Sales\Repositories;

use App\Models\SalesCustomer;
use Illuminate\Support\Facades\Hash;

class SalesRepository
{
    private $sales_customer_model;

    public function __construct(SalesCustomer $sales_customer_model)
    {
        $this->sales_customer_model = $sales_customer_model;
    }

    public function getAllBySalesId($id) 
    {
        return $this->sales_customer_model->where('sales_id', $id)->with('order')->get();    
    }

    public function getAll() 
    {
        return $this->sales_customer_model->where->with('sales', 'customer')->get();    
    }

    public function getSchedule($id, $filters = []) 
    {
        $query = $this->sales_customer_model->where('sales_id', $id)->where('visit_at', '>=', now())->with('order');
        
        if(!empty($filters) && (isset($filters['from']) && isset($filters['to']))) {
            $query->whereDate('visit_at', '>=', $filters['from'])
                  ->whereDate('visit_at', '<=', $filters['to']);
        }
        
        return $query->get();
    }

    public function getPastSchedule($id)
    {
        return $this->sales_customer_model->where('sales_id', $id)->where('visit_at', '<', now())->with('order')->get();
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