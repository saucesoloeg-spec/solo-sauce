<?php

namespace App\Console\Commands;

use App\Domains\Customers\Services\CustomerService;
use App\Domains\Odoo\Services\OdooAuthService;
use Illuminate\Console\Command;

class ImportCustomersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customer data from odoo to the database';

    protected $odoo_service;
    protected $customer_service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OdooAuthService $odoo_service, CustomerService $customer_service)
    {
        parent::__construct();
        $this->odoo_service     = $odoo_service;
        $this->customer_service = $customer_service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $db_customers = $this->customer_service->getCustomersFromDB();
        $cusomters_ids = $db_customers['response_data']->pluck('id')->toArray();

        $filters = [
            'limit' => 100,
            'page'  => 1
        ];
        
        $odoo_customers = $this->odoo_service->getCustomers($filters)['data'];

        while($filters['page'] <= $odoo_customers['pagination']['total_pages']) {
            $odoo_customers = $this->odoo_service->getCustomers($filters)['data'];
            
            if(!empty($odoo_customers)) {
                foreach($odoo_customers['customers'] as $key => $odoo_customer) {
                    if(!in_array($odoo_customer['id'], $cusomters_ids)) {
                        // filter email, phone and name from odoo response should not be null or empty string
                        if(!empty($odoo_customer['email']) && !empty($odoo_customer['phone']) && !empty($odoo_customer['name'])) {
                            // create customer
                            $customer_data = [
                                'id'       => $odoo_customer['id'],
                                'sales_id' => null, // assign to sales later
                                'name'     => $odoo_customer['name'],
                                'phone'    => $odoo_customer['phone'],
                                'email'    => $odoo_customer['email'],
                                'address'  => $odoo_customer['address'],
                                'zone'     => $odoo_customer['city'],
                                'city'     => $odoo_customer['state'],
                            ];
                            $customer = $this->customer_service->createCustomers($customer_data);
                            
                            echo "Customer ID: {$odoo_customer['id']} - {$odoo_customer['name']} imported successfully.\n";
                        }
                        else {
                            echo "Customer ID: {$odoo_customer['id']} - {$odoo_customer['name']} skipped due to missing email, phone or name.\n";
                        }
                    }
                }
            }
            
            $filters['page']++;
        }
    }
}
