<?php

namespace App\Http\Repositories;

use App\Models\Order;

class OrderRepository
{
    private $model;

    public function __construct(Order $order) 
    {
        $this->model = $order;
    }

    public function getAll() 
    {
        return $this->model->all();    
    }

    public function getById($id) 
    {
        return $this->model->with(['customer', 'sales'])->find($id);    
    }

}