<?php

namespace App\Http\Repositories;

use App\Models\Admin;
use App\Models\Order;
use App\Models\MonthlyIncome;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminRepository
{
    private $model;
    private $monthly_income_model;
    private $order_model;
    private $survey_answer_model;

    public function __construct(Admin $admin, MonthlyIncome $monthlyIncome, Order $order, SurveyAnswer $surveyAnswer) 
    {
        $this->model                = $admin;
        $this->monthly_income_model = $monthlyIncome;
        $this->order_model          = $order;
        $this->survey_answer_model  = $surveyAnswer;
    }

    public function getAll() 
    {
        return $this->model->all();    
    }
    
    public function store($request) 
    {
        return $this->model->create([
            'name'     => $request['name'],
            'email'    => $request['email'],
            'password' => Hash::make($request['password']),
            'role'     => isset($request['role']) ? $request['role'] : 'admin'
        ]);    
    }

    public function delete($id) 
    {
        return $this->model->where('id', $id)->delete();    
    }

    public function PastMonthlyIncome() 
    {
        $lastMonth = now()->subMonthsNoOverflow(2);
        $endDate   = $lastMonth->copy()->endOfMonth();
        
        return $this->monthly_income_model->where('collect_date', $endDate->format('Y-m-d'))->first();   
    }

    public function monthlyIncome() 
    {
        $lastMonth = now()->subMonthNoOverflow();
        $endDate   = $lastMonth->copy()->endOfMonth();
        
        return $this->monthly_income_model->where('collect_date', $endDate->format('Y-m-d'))->first();   
    }

    public function monthlyKpis()
    {
        $now = now();
        $previousMonth = $now->copy()->subMonthNoOverflow();

        return [
            'rolling' => $this->getKpisForPeriod($now->copy()->subYearNoOverflow(), $now),
            'current' => $this->getKpisForPeriod($now->copy()->startOfMonth(), $now),
            'previous' => $this->getKpisForPeriod(
                $previousMonth->copy()->startOfMonth(),
                $previousMonth->copy()->endOfMonth()->endOfDay()
            ),
        ];
    }

    private function getKpisForPeriod($start, $end)
    {
        $orders = $this->order_model->whereBetween('orders.created_at', [$start, $end]);
        $firstOrder = '(
            SELECT o2.id
            FROM orders o2
            WHERE o2.customer_id = orders.customer_id
              AND o2.deleted_at IS NULL
            ORDER BY o2.created_at, o2.id
            LIMIT 1
        )';

        $newDeals = (clone $orders)
            ->whereRaw('orders.id = ' . $firstOrder)
            ->count();

        $reorders = (clone $orders)
            ->whereRaw('orders.id <> ' . $firstOrder)
            ->count();

        $surveyPairs = $this->survey_answer_model
            ->whereBetween('created_at', [$start, $end])
            ->select('sales_customer_id', 'customer_id')
            ->distinct()
            ->toBase();

        $surveys = DB::query()
            ->fromSub($surveyPairs, 'survey_pairs')
            ->count();

        return [
            'new_deals' => $newDeals,
            'reorders' => $reorders,
            'surveys' => $surveys,
        ];
    }

    public function ordersByStatusForRollingYear()
    {
        [$start, $end] = $this->getRollingYearRange();
        
        return $this->order_model
                    ->whereBetween('created_at', [
                        $start,
                        $end
                    ])
                    ->selectRaw('state, COUNT(*) as total')
                    ->groupBy('state')
                    ->pluck('total', 'state');
    }

    private function getRollingYearRange()
    {
        $now = now();

        return [
            $now->copy()->subYearNoOverflow(),
            $now,
        ];
    }

    public function topNewDealsByRepresentative($limit = 5)
    {
        [$start, $end] = $this->getRollingYearRange();

        return $this->order_model
                    ->join('sales', 'sales.id', '=', 'orders.sales_id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->whereNotNull('orders.sales_id')
                    ->whereRaw('orders.created_at = (
                        SELECT MIN(o2.created_at)
                        FROM orders o2
                        WHERE o2.customer_id = orders.customer_id
                    )')
                    ->groupBy('sales.id', 'sales.name')
                    ->selectRaw('sales.id as sales_id, sales.name as sales_name, COUNT(*) as total')
                    ->orderByDesc('total')
                    ->limit($limit)
                    ->get();
    }

    public function topReordersByRepresentative($limit = 5)
    {
        [$start, $end] = $this->getRollingYearRange();

        return $this->order_model
                    ->join('sales', 'sales.id', '=', 'orders.sales_id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->whereNotNull('orders.sales_id')
                    ->whereRaw('orders.created_at > (
                        SELECT MIN(o2.created_at)
                        FROM orders o2
                        WHERE o2.customer_id = orders.customer_id
                    )')
                    ->groupBy('sales.id', 'sales.name')
                    ->selectRaw('sales.id as sales_id, sales.name as sales_name, COUNT(*) as total')
                    ->orderByDesc('total')
                    ->limit($limit)
                    ->get();
    }

    public function topSurveyAnswersByRepresentative($limit = 5)
    {
        [$start, $end] = $this->getRollingYearRange();

        $surveyPairs = $this->survey_answer_model
                    ->join('sales', 'sales.id', '=', 'survey_answers.sales_id')
                    ->whereBetween('survey_answers.created_at', [$start, $end])
                    ->whereNotNull('survey_answers.sales_id')
                    ->select(
                        'survey_answers.sales_id',
                        'sales.name as sales_name',
                        'survey_answers.sales_customer_id',
                        'survey_answers.customer_id'
                    )
                    ->distinct()
                    ->toBase();

        return DB::query()
                    ->fromSub($surveyPairs, 'survey_pairs')
                    ->select('sales_id', 'sales_name')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('sales_id', 'sales_name')
                    ->orderByDesc('total')
                    ->limit($limit)
                    ->get();
    }
    
    public function yearlyIncome() 
    {
        $lastYear  = now();
        $startDate = $lastYear->copy()->startOfYear();
        $endDate   = $lastYear->copy()->endOfYear();
        
        return $this->monthly_income_model->whereBetween('collect_date', [$startDate, $endDate])->get();   
    }

}
