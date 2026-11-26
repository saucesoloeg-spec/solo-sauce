<?php

namespace Tests\Feature;

use App\Http\Repositories\AdminRepository;
use App\Models\Admin;
use App\Models\MonthlyIncome;
use App\Models\Order;
use App\Models\SurveyAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminDashboardKpisTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ],
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');
        Carbon::setTestNow('2026-11-26 12:00:00');

        Schema::create('orders', function ($table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->string('state');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sales', function ($table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('survey_answers', function ($table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('sales_customer_id');
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_calculates_rolling_year_totals_and_compares_month_to_date_with_last_month(): void
    {
        $now = now();
        $previousMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();

        DB::table('sales')->insert([
            ['id' => 1, 'name' => 'Alex'],
            ['id' => 2, 'name' => 'Sam'],
        ]);

        $this->insertOrder(1, $previousMonthStart, 1, 'pending');
        $this->insertOrder(1, $now->copy()->subMinutes(2), 1, 'confirmed');
        $this->insertOrder(2, $now->copy()->subMinute(), 1, 'completed');
        $this->insertOrder(3, $previousMonthStart, 2, 'shipped');
        $this->insertOrder(3, $previousMonthStart->copy()->addDay(), 2, 'cancelled');
        $this->insertOrder(4, $now->copy()->subYearNoOverflow()->subSecond(), 1, 'returned');

        $this->insertSurvey(1, 65, 1, $now->copy()->subMinute());
        $this->insertSurvey(1, 65, 1, $now->copy()->subMinute());
        $this->insertSurvey(1, 65, 2, $now->copy()->subMinute());
        $this->insertSurvey(2, 55, 1, $previousMonthStart);
        $this->insertSurvey(2, 55, 1, $previousMonthStart);
        $this->insertSurvey(2, 55, 2, $previousMonthStart);
        $this->insertSurvey(1, 44, 1, $now->copy()->subYearNoOverflow()->subSecond());

        $repository = new AdminRepository(new Admin(), new MonthlyIncome(), new Order(), new SurveyAnswer());

        $this->assertSame([
            'rolling' => ['new_deals' => 3, 'reorders' => 2, 'surveys' => 4],
            'current' => ['new_deals' => 1, 'reorders' => 1, 'surveys' => 2],
            'previous' => ['new_deals' => 2, 'reorders' => 1, 'surveys' => 2],
        ], $repository->monthlyKpis());

        $this->assertSame(
            ['Alex' => 2, 'Sam' => 1],
            $repository->topNewDealsByRepresentative()->pluck('total', 'sales_name')->map(fn ($total) => (int) $total)->sortKeys()->all()
        );
        $this->assertSame(
            ['Alex' => 1, 'Sam' => 1],
            $repository->topReordersByRepresentative()->pluck('total', 'sales_name')->map(fn ($total) => (int) $total)->sortKeys()->all()
        );
        $this->assertSame(
            ['Alex' => 2, 'Sam' => 2],
            $repository->topSurveyAnswersByRepresentative()->pluck('total', 'sales_name')->map(fn ($total) => (int) $total)->sortKeys()->all()
        );
        $this->assertSame([
            'cancelled' => 1,
            'completed' => 1,
            'confirmed' => 1,
            'pending' => 1,
            'shipped' => 1,
        ], $repository->ordersByStatusForRollingYear()->map(fn ($total) => (int) $total)->sortKeys()->all());
    }

    private function insertOrder($customerId, Carbon $createdAt, $salesId, $state): void
    {
        DB::table('orders')->insert([
            'customer_id' => $customerId,
            'sales_id' => $salesId,
            'state' => $state,
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $createdAt->format('Y-m-d H:i:s'),
        ]);
    }

    private function insertSurvey($salesId, $visitId, $customerId, Carbon $createdAt): void
    {
        DB::table('survey_answers')->insert([
            'sales_id' => $salesId,
            'sales_customer_id' => $visitId,
            'customer_id' => $customerId,
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $createdAt->format('Y-m-d H:i:s'),
        ]);
    }
}
