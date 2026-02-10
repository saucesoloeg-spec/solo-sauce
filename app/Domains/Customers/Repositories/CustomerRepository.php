<?php

namespace App\Domains\Customers\Controllers;

use App\Models\Customer;

class CustomerRepository
{
    private $customer_model;

    public function __construct(Customer $customer_model)
    {
        $this->customer_model = $customer_model;
    }

    public function getAssignedCustomers($sales_id)
    {
        return $this->customer_model->join('sales_customers', 'customers.id', '=', 'sales_customers.customer_id')
            ->where('sales_customers.sales_id', $sales_id)
            ->select('customers.*')
            ->get();
    }

    public function find($id)
    {
        return $this->customer_model->find($id);
    }

    public function create(array $data)
    {
        return $this->customer_model->create($data);
    }

}