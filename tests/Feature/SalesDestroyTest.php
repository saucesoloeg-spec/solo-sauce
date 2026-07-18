<?php

namespace Tests\Feature;

use App\Http\Services\SalesService;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class SalesDestroyTest extends TestCase
{
    public function test_sales_can_be_deleted_via_destroy_route()
    {
        $admin = new Admin(['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com']);
        Auth::guard('admin')->login($admin);

        $salesService = Mockery::mock(SalesService::class);
        $salesService->shouldReceive('deleteSales')->once()->with(42)->andReturn([
            'response_code' => 200,
            'response_message' => 'Sales deleted successfully.',
            'response_data' => null,
        ]);

        $this->app->instance(SalesService::class, $salesService);

        $response = $this->deleteJson(route('sales.destroy', ['id' => 42]));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Sales deleted successfully.',
            ]);
    }
}
