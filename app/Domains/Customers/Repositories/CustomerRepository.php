<?php

namespace App\Domains\Customers\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

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
        return $this->customer_model
            ->with(['orders' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->find($id);
    }

    public function getCustomerStatistics($customer_id)
    {
        $customer = $this->customer_model->withCount([
                'orders' => function ($query) {
                    $query->where('created_at', '>=', now()->subMonths(12));
                },
                // Count distinct surveys by sales_customer_id (one survey per visit)
                'answers as surveys_count' => function ($query) {
                    $query->where('created_at', '>=', now()->subMonths(12))
                          ->select(DB::raw('COUNT(DISTINCT sales_customer_id)'));
                }
            ])->find($customer_id);

        if (!$customer) {
            return null;
        }

        return [
            'total_orders'  => $customer->orders_count,
            'total_surveys' => $customer->surveys_count,
        ];
    }

    public function create(array $data)
    {
        return $this->customer_model->create([
            'id'              => $data['id'],
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
            'latitude'        => (float)$data['latitude'] ?? null,
            'longitude'       => (float)$data['longitude'] ?? null,
        ]);
    }

    public function getTodayVisit($sales_id, $customer_id)
    {
        $today = now()->toDateString();
        
        return $this->customer_model
            ->join('sales_customers', 'customers.id', '=', 'sales_customers.customer_id')
            ->where('sales_customers.sales_id', $sales_id)
            ->where('sales_customers.customer_id', $customer_id)
            ->whereDate('sales_customers.visit_at', $today)
            ->select('sales_customers.*')
            ->first();
    }

}