<?php

namespace App\Domains\Customers\Repositories;

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
        return $this->customer_model->leftJoin('sales_customers', 'customers.id', '=', 'sales_customers.customer_id')
            ->where('sales_customers.sales_id', $sales_id)
            ->orWhere('customers.sales_id', $sales_id)
            ->select('customers.*')
            ->get();
    }

    public function getAll()
    {
        return $this->customer_model->all();
    }

    public function find($id)
    {
        return $this->customer_model->find($id);
    }

    public function create(array $data)
    {
        return $this->customer_model->create([
            'sales_id'        => $data['sales_id'] ?? null,
            'name'            => $data['name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'via'             => $data['via'],
            'address'         => $data['address'],
            'city'            => $data['city'] ?? null,
            'state'           => $data['state'] ?? null,
            'city_odoo_id'    => $data['city_id'],
            'state_odoo_id'   => $data['state_id'],
            'country_odoo_id' => $data['country_id'],
            'latitude'        => $data['latitude'] ?? null,
            'longitude'       => $data['longitude'] ?? null,
        ]);
    }

}