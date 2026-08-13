<?php

namespace Tests\Feature;

use Tests\TestCase;

class CustomerServiceTest extends TestCase
{
    public function test_get_all_returns_empty_array_when_no_customers_exist()
    {
        $response = app(\App\Http\Services\CustomerService::class)->getAll();

        $this->assertSame(404, $response['response_code']);
        $this->assertIsArray($response['response_data']);
        $this->assertSame([], $response['response_data']);
    }
}
