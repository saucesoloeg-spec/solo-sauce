<?php

namespace App\Http\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    private $model;

    public function __construct(Customer $customer) 
    {
        $this->model = $customer;
    }

    public function getAll() 
    {
        return $this->model->all();    
    }

}