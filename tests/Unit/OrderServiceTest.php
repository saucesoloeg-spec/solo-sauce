<?php

namespace Tests\Unit;

use App\Domains\Orders\Repositories\OrderRepository;
use App\Domains\Orders\Services\OrderService;
use App\Domains\Odoo\Services\OdooAuthService;
use App\Models\Order;
use Mockery;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    public function test_it_saves_the_odoo_order_name_without_confirming_or_creating_an_invoice(): void
    {
        $data = $this->orderData();
        $order = new Order();
        $order->id = 10;

        $repository = Mockery::mock(OrderRepository::class);
        $repository->shouldReceive('saveOrder')->once()->with($data, 42)->andReturn($order);
        $repository->shouldReceive('saveInvoiceId')->once()->with($order, 'S00042')->andReturnUsing(function ($order, $invoiceId) {
            $order->invoice_id = $invoiceId;

            return $order;
        });

        $odooService = Mockery::mock(OdooAuthService::class);
        $odooService->shouldReceive('sendOrderToOdoo')->once()->andReturn([
            'success' => true,
            'data' => ['id' => 42, 'name' => 'S00042'],
        ]);
        $odooService->shouldNotReceive('confirmOrderInOdoo');
        $odooService->shouldNotReceive('createInvoiceForOrder');

        $service = new OrderService($repository, $odooService);
        $response = $service->saveOrder($data);

        $this->assertSame(201, $response['response_code']);
        $this->assertSame('S00042', $response['response_data']->invoice_id);
        $this->assertSame('S00042', $response['response_data']->attributesToArray()['invoice_id']);
        $this->assertArrayNotHasKey('invoice_error', $response);
    }

    public function test_it_reports_when_odoo_does_not_return_an_order_name(): void
    {
        $data = $this->orderData();
        $order = new Order();
        $order->id = 10;

        $repository = Mockery::mock(OrderRepository::class);
        $repository->shouldReceive('saveOrder')->once()->with($data, 42)->andReturn($order);
        $repository->shouldNotReceive('saveInvoiceId');

        $odooService = Mockery::mock(OdooAuthService::class);
        $odooService->shouldReceive('sendOrderToOdoo')->once()->andReturn([
            'success' => true,
            'data' => ['id' => 42],
        ]);
        $odooService->shouldNotReceive('confirmOrderInOdoo');
        $odooService->shouldNotReceive('createInvoiceForOrder');

        $service = new OrderService($repository, $odooService);
        $response = $service->saveOrder($data);

        $this->assertSame(502, $response['response_code']);
        $this->assertSame('Order was created in Odoo but its name was missing from the response', $response['response_message']);
        $this->assertSame($order, $response['response_data']);
    }

    private function orderData()
    {
        return [
            'customer_id' => 1,
            'type_id' => 1,
            'amount_total' => 100,
            'payment_method' => 'cash',
            'delivery_date' => '2026-11-07',
        ];
    }
}
