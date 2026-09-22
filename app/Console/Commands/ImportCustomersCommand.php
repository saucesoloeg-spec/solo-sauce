<?php

namespace App\Console\Commands;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
            'page'  => 1,
        ];
        $imported = 0;
        $existing = 0;
        $skipped = 0;
        $totalPages = 1;

        try {
            do {
                $page = null;
                $attempt = 0;

                while ($page === null && $attempt < 3) {
                    $attempt++;

                    try {
                        $page = $this->odoo_service->getCustomers($filters);
                    } catch (\Throwable $exception) {
                        if ($attempt === 3) {
                            throw $exception;
                        }

                        usleep(500000);
                    }
                }

                $data = $page['data'] ?? [];
                $totalPages = (int) ($data['pagination']['total_pages'] ?? $filters['page']);

                foreach ($data['customers'] ?? [] as $odooCustomer) {
                    $name = trim((string) ($odooCustomer['name'] ?? ''));
                    $phone = trim((string) ($odooCustomer['phone'] ?? ''));

                    if ($phone === '') {
                        $phone = trim((string) ($odooCustomer['mobile'] ?? ''));
                    }

                    if ($name === '' || empty($odooCustomer['id'])) {
                        $skipped++;
                        continue;
                    }

                    $existingPhone = $phone !== '' && Customer::withTrashed()
                        ->where('phone', $phone)
                        ->where('id', '!=', $odooCustomer['id'])
                        ->exists();

                    if ($existingPhone) {
                        $skipped++;
                        continue;
                    }

                    $email = trim((string) ($odooCustomer['email'] ?? '')) ?: null;
                    if ($email && Customer::withTrashed()
                        ->where('email', $email)
                        ->where('id', '!=', $odooCustomer['id'])
                        ->exists()) {
                        $email = null;
                    }

                    $customer = Customer::withTrashed()->updateOrCreate(
                        ['id' => $odooCustomer['id']],
                        [
                            'name'            => $name,
                            'phone'           => $phone !== '' ? $phone : null,
                            'email'           => $email,
                            'is_imported'     => true,
                            'address'         => $odooCustomer['address'] ?? '',
                            'city'            => $odooCustomer['city'] ?? '',
                            'state'           => $odooCustomer['state'] ?? '',
                            'country_odoo_id' => $odooCustomer['country_id'] ?? null,
                            'state_odoo_id'   => $odooCustomer['state_id'] ?? null,
                            'city_odoo_id'    => $odooCustomer['city_id'] ?? null,
                            'latitude'        => $odooCustomer['latitude'] ?? null,
                            'longitude'       => $odooCustomer['longitude'] ?? null,
                        ]
                    );

                    if ($customer->trashed()) {
                        $customer->restore();
                    }

                    if ($customer->wasRecentlyCreated) {
                        $imported++;
                    } else {
                        $existing++;
                    }
                }

                $filters['page']++;
            } while ($filters['page'] <= $totalPages);
        } finally {
            Log::channel('customer_import')->info(sprintf(
                'Customer import run at %s. Imported new: %d. Existing updated: %d. Skipped: %d.',
                now()->toDateTimeString(),
                $imported,
                $existing,
                $skipped
            ));
        }

        return self::SUCCESS;
    }
}
