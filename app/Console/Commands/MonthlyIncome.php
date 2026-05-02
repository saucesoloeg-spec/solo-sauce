<?php

namespace App\Console\Commands;

use App\Models\MonthlyIncome as ModelsMonthlyIncome;
use Illuminate\Console\Command;
use App\Models\SalesCustomer;
use App\Models\SurveyAnswer;
use App\Models\Order;

class MonthlyIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the monthly income monthly in MonthlyIncome Table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the previous month's income
        $lastMonth = now()->subMonth();
        $startDate = $lastMonth->copy()->startOfMonth();
        $endDate   = $lastMonth->copy()->endOfMonth();

        $total_visits     = SalesCustomer::class::whereBetween('created_at', [$startDate, $endDate])->get();
        $completed_visits = $total_visits->where('status', 'completed')->count();
        $open_visits      = $total_visits->where('status', 'pending')->count();
        $delayed_visits   = $total_visits->where('status', 'delayed')->count();
        $canceled_visits  = $total_visits->where('status', 'canceled')->count();

        $total_orders     = Order::whereBetween('created_at', [$startDate, $endDate])->get();
        $new_orders       = Order::whereBetween('created_at', [$startDate, $endDate])
                                  ->whereRaw('created_at = (
                                      SELECT MIN(o2.created_at)
                                      FROM orders o2
                                      WHERE o2.customer_id = orders.customer_id
                                  )')
                                  ->get();
        $reorders         = Order::whereBetween('created_at', [$startDate, $endDate])
                                 ->whereRaw('created_at > (
                                     SELECT MIN(o2.created_at)
                                     FROM orders o2
                                     WHERE o2.customer_id = orders.customer_id
                                 )')
                                 ->get();
        
        $orders_sum       = $total_orders->sum('amount_total');

        $total_surveys    = SurveyAnswer::whereBetween('created_at', [$startDate, $endDate])
                                    ->selectRaw('COUNT(DISTINCT sales_customer_id, customer_id) as total')
                                    ->value('total');

        $added = ModelsMonthlyIncome::create([
            'income'           => $orders_sum ?? 0, // Default to 0 if no income
            'collect_date'     => $lastMonth->copy()->endOfMonth()->format('Y-m-d'),
            'total_visits'     => $total_visits->count(), 
            'completed_visits' => $completed_visits, 
            'open_visits'      => $open_visits, 
            'delayed_visits'   => $delayed_visits,
            'canceled_visits'  => $canceled_visits,
            'total_orders'     => $total_orders->count(),
            'total_reorders'   => $reorders->count(),
            'total_new_orders' => $new_orders->count(),
            'total_surveys'    => $total_surveys ?? 0, // Default to 0 if no surveys
        ]);

        if($added) {
            $this->info("Monthly income summarized successfully: $orders_sum");
        } else {
            $this->error('Failed to add monthly income.');
        }
    }
}
