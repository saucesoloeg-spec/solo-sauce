<?php

namespace Tests\Feature;

use App\Models\Sales;
use App\Models\SalesCustomer;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SalesVisitNotesTest extends TestCase
{
    use RefreshDatabase;

    private function makeSales(string $suffix = '1'): Sales
    {
        $id = DB::table('sales')->insertGetId([
            'uuid'       => (string) Str::uuid(),
            'name'       => "Sales {$suffix}",
            'email'      => "sales{$suffix}@example.com",
            'phone'      => "0100000000{$suffix}",
            'password'   => bcrypt('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Sales::find($id);
    }

    private function makeCustomer(int $salesId, string $suffix = '1'): int
    {
        return DB::table('customers')->insertGetId([
            'sales_id'        => $salesId,
            'name'            => "Customer {$suffix}",
            'email'           => "customer{$suffix}@example.com",
            'commercial_name' => 'Shop',
            'taxtation_name'  => 'Shop',
            'phone'           => "0120000000{$suffix}",
            'address'         => 'Street',
            'city'            => 'City',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    private function makeVisit(int $salesId, int $customerId, array $overrides = []): SalesCustomer
    {
        return SalesCustomer::create(array_merge([
            'sales_id'    => $salesId,
            'customer_id' => $customerId,
            'visit_at'    => now(),
            'status'      => 'pending',
            'notes'       => 'admin note',
        ], $overrides));
    }

    private function makeSurvey(string $q = 'Q1'): Survey
    {
        return Survey::create(['question_en' => $q, 'question_ar' => $q]);
    }

    // ---- PUT /api/sales/visits/{id}/status ----

    public function test_update_visit_status_requires_authentication()
    {
        $this->postJson('/api/sales/visits/1/status', ['status' => 'completed'])->assertStatus(401);
    }

    public function test_sales_can_update_visit_status_and_sales_notes_without_touching_admin_notes()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $visit    = $this->makeVisit($sales->id, $customer);
        Sanctum::actingAs($sales, [], 'sales');

        $this->postJson("/api/sales/visits/{$visit->id}/status", [
            'status'      => 'completed',
            'sales_notes' => 'Customer was happy',
        ])->assertStatus(200)->assertJsonPath('response_data.status', 'completed');

        $visit->refresh();
        $this->assertSame('completed', $visit->status);
        $this->assertSame('Customer was happy', $visit->sales_notes);
        $this->assertSame('admin note', $visit->notes);
    }

    public function test_update_without_sales_notes_keeps_existing_sales_notes()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $visit    = $this->makeVisit($sales->id, $customer, ['sales_notes' => 'keep me']);
        Sanctum::actingAs($sales, [], 'sales');

        $this->postJson("/api/sales/visits/{$visit->id}/status", ['status' => 'cancelled'])->assertStatus(200);

        $visit->refresh();
        $this->assertSame('cancelled', $visit->status);
        $this->assertSame('keep me', $visit->sales_notes);
    }

    public function test_update_visit_status_rejects_invalid_status()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $visit    = $this->makeVisit($sales->id, $customer);
        Sanctum::actingAs($sales, [], 'sales');

        $this->postJson("/api/sales/visits/{$visit->id}/status", ['status' => 'bogus'])
            ->assertStatus(422)->assertJsonValidationErrors('status');
    }

    public function test_sales_cannot_update_another_sales_visit()
    {
        $owner    = $this->makeSales('1');
        $other    = $this->makeSales('2');
        $customer = $this->makeCustomer($owner->id);
        $visit    = $this->makeVisit($owner->id, $customer);
        Sanctum::actingAs($other, [], 'sales');

        $this->postJson("/api/sales/visits/{$visit->id}/status", ['status' => 'completed'])->assertStatus(404);

        $this->assertSame('pending', $visit->fresh()->status);
    }

    // ---- POST /api/surveys ----

    public function test_store_survey_still_requires_answers_without_sales_notes()
    {
        Sanctum::actingAs($this->makeSales(), [], 'sales');

        $this->postJson('/api/surveys', [])->assertStatus(422)->assertJsonValidationErrors('answers');
    }

    public function test_store_survey_with_sales_notes_requires_customer_id()
    {
        Sanctum::actingAs($this->makeSales(), [], 'sales');

        $this->postJson('/api/surveys', ['sales_notes' => 'Customer not present'])
            ->assertStatus(422)->assertJsonValidationErrors('customer_id');
    }

    public function test_store_survey_with_only_sales_notes_completes_pending_visit_without_answers()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $visit    = $this->makeVisit($sales->id, $customer);
        Sanctum::actingAs($sales, [], 'sales');

        $this->postJson('/api/surveys', [
            'customer_id' => $customer,
            'sales_notes' => 'Customer not present',
        ])->assertStatus(201);

        $visit->refresh();
        $this->assertSame('completed', $visit->status);
        $this->assertSame('Customer not present', $visit->sales_notes);
        $this->assertFalse((bool) $visit->survey);
        $this->assertSame(0, SurveyAnswer::count());
    }

    public function test_store_survey_with_only_sales_notes_creates_visit_when_none_pending()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        Sanctum::actingAs($sales, [], 'sales');

        $this->postJson('/api/surveys', [
            'customer_id' => $customer,
            'sales_notes' => 'Shop closed',
        ])->assertStatus(201);

        $visit = SalesCustomer::where('customer_id', $customer)->first();
        $this->assertNotNull($visit);
        $this->assertSame($sales->id, (int) $visit->sales_id);
        $this->assertSame('Shop closed', $visit->sales_notes);
    }

    public function test_store_survey_with_answers_and_sales_notes_saves_both()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $survey   = $this->makeSurvey();
        $visit    = $this->makeVisit($sales->id, $customer);
        Sanctum::actingAs($sales, [], 'sales');

        $this->postJson('/api/surveys', [
            'sales_notes' => 'Partial survey',
            'customer_id' => $customer,
            'answers'     => [['survey_id' => $survey->id, 'answer' => 'yes', 'customer_id' => $customer]],
        ])->assertStatus(201);

        $visit->refresh();
        $this->assertTrue((bool) $visit->survey);
        $this->assertSame('Partial survey', $visit->sales_notes);
        $this->assertSame(1, SurveyAnswer::where('sales_customer_id', $visit->id)->count());
    }

    public function test_store_survey_with_answers_only_still_works()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $survey   = $this->makeSurvey();
        $visit    = $this->makeVisit($sales->id, $customer);
        Sanctum::actingAs($sales, [], 'sales');

        $this->postJson('/api/surveys', [
            'answers' => [['survey_id' => $survey->id, 'answer' => 'yes', 'customer_id' => $customer]],
        ])->assertStatus(201);

        $visit->refresh();
        $this->assertSame('completed', $visit->status);
        $this->assertTrue((bool) $visit->survey);
        $this->assertNull($visit->sales_notes);
    }

    // ---- GET /api/surveys/customer ----

    public function test_get_customer_answers_includes_note_only_visits_with_sales_notes()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $this->makeSurvey();
        $visit    = $this->makeVisit($sales->id, $customer, [
            'status'      => 'completed',
            'sales_notes' => 'Customer not present',
        ]);
        Sanctum::actingAs($sales, [], 'sales');

        $response = $this->getJson("/api/surveys/customer?customer_id={$customer}")->assertStatus(200);

        $response->assertJsonPath('response_data.0.visit_id', $visit->id)
            ->assertJsonPath('response_data.0.sales_notes', 'Customer not present')
            ->assertJsonPath('response_data.0.surveys.0.answer', null);
    }

    public function test_get_customer_answers_for_single_visit_returns_sales_notes()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $this->makeSurvey();
        $visit    = $this->makeVisit($sales->id, $customer, ['sales_notes' => 'Customer not present']);
        Sanctum::actingAs($sales, [], 'sales');

        $this->getJson("/api/surveys/customer?customer_id={$customer}&visit_id={$visit->id}")
            ->assertStatus(200)
            ->assertJsonPath('response_data.0.sales_notes', 'Customer not present');
    }

    public function test_get_customer_answers_excludes_visits_without_answers_or_notes()
    {
        $sales    = $this->makeSales();
        $customer = $this->makeCustomer($sales->id);
        $this->makeVisit($sales->id, $customer);
        Sanctum::actingAs($sales, [], 'sales');

        $this->getJson("/api/surveys/customer?customer_id={$customer}")
            ->assertStatus(200)
            ->assertJsonCount(0, 'response_data');
    }
}
