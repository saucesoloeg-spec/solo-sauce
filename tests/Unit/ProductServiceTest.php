<?php

namespace Tests\Unit;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Domains\Products\Services\ProductService;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    public function test_it_retrieves_stock_balance_from_odoo_with_filters(): void
    {
        $filters = [
            'location_id' => 12,
            'product_id' => 34,
        ];
        $odooService = Mockery::mock(OdooAuthService::class);
        $odooService->shouldReceive('getStockBalanceFromOdoo')
            ->once()
            ->with($filters)
            ->andReturn([
                'success' => true,
                'data' => ['quantity' => 8],
            ]);

        $service = new ProductService($odooService);

        $this->assertSame([
            'response_code' => 200,
            'response_message' => 'Stock balance retrieved successfully',
            'response_data' => ['quantity' => 8],
        ], $service->getStockBalance($filters));
    }
}
