<?php

namespace App\Console\Commands;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OdooAuthService $odoo_service)
    {
        parent::__construct();
        $this->odoo_service = $odoo_service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filters = [
            'limit' => 100,
            'page'  => 1
        ];
        $odoo_customers = $this->odoo_service->getCustomers($filters)['data'];
        
        for ($i = 1; $i <= ceil($odoo_customers['total']/$odoo_customers['limit']); $i++) {
            $filters['page'] = $i;
            $customer = $this->odoo_service->getCustomerById($odoo_customers['items'][$i - 1]['id']);
            dd($customer);
            $odoo_customers = $this->odoo_service->getCustomers($filters);
        }

        dd($odoo_customers);
    }
}
