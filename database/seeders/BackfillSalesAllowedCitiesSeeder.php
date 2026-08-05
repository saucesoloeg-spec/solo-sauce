<?php

namespace Database\Seeders;

use App\Models\Sales;
use App\Models\SalesAllowedCity;
use Illuminate\Database\Seeder;

class BackfillSalesAllowedCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sales::query()
            ->whereNotNull('city_odoo_id')
            ->chunkById(200, function ($salesRows) {
                foreach ($salesRows as $sales) {
                    SalesAllowedCity::firstOrCreate([
                        'sales_id' => $sales->id,
                        'city_odoo_id' => (int) $sales->city_odoo_id,
                    ]);
                }
            });
    }
}
