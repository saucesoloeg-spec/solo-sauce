<?php

namespace Tests\Unit;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\SalesRepository;
use App\Http\Services\SalesService;
use App\Models\Sales;
use Mockery;
use Tests\TestCase;

class SalesServiceLocationUpdateTest extends TestCase
{
    public function test_update_sales_sends_storage_location_to_odoo_and_persists_it_locally()
    {
        $sales = new Sales(['odoo_id' => 42]);
        $salesRepository = Mockery::mock(SalesRepository::class);
        $salesRepository->shouldReceive('getById')->once()->with(7)->andReturn($sales);
        $salesRepository->shouldReceive('update')
            ->once()
            ->with(7, Mockery::on(function ($data) {
                return $data['storage_location_odoo_id'] === 123
                    && !array_key_exists('allowed_city_ids', $data);
            }))
            ->andReturn(1);
        $salesRepository->shouldReceive('syncAllowedCities')
            ->once()
            ->with(7, [10, 11])
            ->andReturn(true);

        $odooService = Mockery::mock(OdooAuthService::class);
        $odooService->shouldReceive('updateOdooAccount')
            ->once()
            ->with([
                'odoo_id' => 42,
                'location_id' => 123,
                'allowed_city_ids' => [10, 11],
                'city_odoo_id' => 10,
            ])
            ->andReturn(['success' => true]);

        $service = new SalesService(
            $salesRepository,
            Mockery::mock(CustomerRepository::class),
            $odooService
        );

        $response = $service->updateSales(7, [
            'storage_location_odoo_id' => 123,
            'allowed_city_ids' => [10, 11],
        ]);

        $this->assertSame(200, $response['response_code']);
    }
}
