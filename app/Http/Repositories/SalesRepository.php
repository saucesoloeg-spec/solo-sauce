<?php

namespace App\Http\Repositories;

use App\Models\Sales;
use App\Models\SalesCustomer;

class SalesRepository
{
    private $model;
    private $sales_customers_model;

    public function __construct(Sales $sales, SalesCustomer $sales_customers) 
    {
        $this->model = $sales;
        $this->sales_customers_model = $sales_customers;
    }

    public function getAll() 
    {
        return $this->model->all();    
    }

    public function getSchedule() 
    {
        return $this->sales_customers_model->with('sales', 'customer')->get();    
    }

    public function updateVisitDate($scheduleId, $visitDate)
    {
        return $this->sales_customers_model->where('id', $scheduleId)->update(['visit_at' => $visitDate]);
    }

    public function deleteSchedule($scheduleId)
    {
        return $this->sales_customers_model->where('id', $scheduleId)->delete();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function createSchedule($data) 
    {
        return $this->sales_customers_model->create([
            'sales_id'    => $data['sales_id'],
            'customer_id' => $data['customer_id'],
            'visit_at'    => $data['visit_date'],
            'status'      => 'pending',
            'notes'       => $data['notes']
        ]);
    }
}