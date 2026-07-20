<?php

namespace Tests\Unit;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Http\Controllers\InventoryController;
use Mockery;
use PHPUnit\Framework\TestCase;

class InventoryControllerTest extends TestCase
{
    public function test_it_summarizes_orders_by_product_quantity(): void
    {
        $odooService = Mockery::mock(OdooAuthService::class);
        $odooService->shouldReceive('getProductByIdFromOdoo')->with(101)->andReturn(['name' => 'Ketchup Sauce']);
        $odooService->shouldReceive('getProductByIdFromOdoo')->with(202)->andReturn(['name' => 'Mayonnaise']);
        $odooService->shouldReceive('getProductByIdFromOdoo')->with(303)->andReturn(null);

        $controller = new InventoryController($odooService);
        $orders = collect([
            (object) [
                'products' => collect([
                    (object) ['product_id' => 101, 'quantity' => 10],
                    (object) ['product_id' => 202, 'quantity' => 3],
                ]),
            ],
            (object) [
                'products' => collect([
                    (object) ['product_id' => 101, 'quantity' => 10],
                    (object) ['product_id' => 202, 'quantity' => 2],
                ]),
            ],
        ]);

        $summary = $controller->summarizeProductsForOrders($orders);

        $this->assertCount(2, $summary);
        $this->assertSame(101, $summary[0]['product_id']);
        $this->assertSame(20, $summary[0]['quantity']);
        $this->assertSame('Ketchup Sauce', $summary[0]['name']);
        $this->assertSame(202, $summary[1]['product_id']);
        $this->assertSame(5, $summary[1]['quantity']);
        $this->assertSame('Mayonnaise', $summary[1]['name']);

        $fallbackName = $controller->summarizeProductsForOrders([
            (object) [
                'products' => collect([
                    (object) ['product_id' => 303, 'quantity' => 4],
                ]),
            ],
        ]);

        $this->assertSame('Product #303', $fallbackName[0]['name']);
    }
}
