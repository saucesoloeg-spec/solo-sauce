<?php

namespace Tests\Feature;

use App\Http\Requests\StoreSalesRequest;
use App\Http\Requests\UpdateSalesRequest;
use Tests\TestCase;

class SalesAllowedCitiesTest extends TestCase
{
    public function test_store_sales_request_accepts_multiple_allowed_city_ids()
    {
        $request = new StoreSalesRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('allowed_city_ids', $rules);
        $this->assertSame('required|array|min:1', $rules['allowed_city_ids']);
        $this->assertSame('required|integer', $rules['allowed_city_ids.*']);
    }

    public function test_update_sales_request_accepts_country_state_and_allowed_city_ids()
    {
        $request = new UpdateSalesRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('country_odoo_id', $rules);
        $this->assertArrayHasKey('state_odoo_id', $rules);
        $this->assertArrayHasKey('allowed_city_ids', $rules);
        $this->assertSame('required|array|min:1', $rules['allowed_city_ids']);
        $this->assertSame('required|integer', $rules['allowed_city_ids.*']);
    }
}
