<?php

namespace App\Http\Repositories;

use App\Models\Sales;

class SalesRepository
{
    private $model;

    public function __construct(Sales $sales) 
    {
        $this->model = $sales;
    }

    public function getAll() 
    {
        return $this->model->all();    
    }

}