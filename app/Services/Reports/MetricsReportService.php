<?php
namespace App\Services\Reports;

use Illuminate\Support\Facades\Cache;
use App\Models\{User, Project, Client, Income, Expenses, Investment};
use App\Services\ModelAggregatorService;
use Carbon\Carbon;
use Closure;
use DB;

class MetricsReportService
{
    public function getUserMetrics($user_id)
    {
        $relatedIncomeScope = fn ($query) => $query->relatedIncome($user_id);
        $relatedInvestmentScope = fn ($query) => $query->relatedInvestment($user_id);
        $userHasIncomeScope = fn ($query) => $query->userHasIncome($user_id);

        $calculateUserEarnings = function ($incomes) use ($user_id) {
            $total = 0;

            foreach ($incomes as $income) {
                $total += $income->calculateEarnings($user_id);
            }
            
            return round($total, 2);
        };

        return [
            'total_awaiting_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['status_id', '1'], ['created_by_user_id', $user_id]]),
            'total_in_progress_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['status_id', '2'], ['created_by_user_id', $user_id]]),
            'total_after_deadline_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['deadline', '<', Carbon::today()->toDateString()], ['created_by_user_id', $user_id], fn ($query) => $query->whereNull('end')]),
            'total_completed_projects_count' =>  
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['status_id', '3'], ['created_by_user_id', $user_id]]),
            'total_active_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], ['user_id', $user_id], [['status_id', '1'], $relatedIncomeScope]),
            'total_completed_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], ['user_id', $user_id], [['status_id', '2'], $relatedIncomeScope]),
            'total_active_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], ['user_id', $user_id], [['status_id', '1'], $relatedInvestmentScope]),
            'total_after_date_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], ['user_id', $user_id], [['status_id', '1'], ['date', '<', Carbon::today()->toDateString()], $relatedInvestmentScope]),
            'total_completed_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], ['user_id', $user_id], [['status_id', '2'], $relatedInvestmentScope]),
            'total_investor_awaiting_repayment_sum' => 
                ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], [['status_id', '1'], ['user_id', $user_id]], \DB::raw('(amount + amount * (interest_rate / 100.0)) - total_repayment'), fn ($value) => round($value, 2)),
            'total_user_awaiting_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'get', ['incomes'], ['user_id', $user_id], [['status_id', '1'], $userHasIncomeScope], null, $calculateUserEarnings),
        ];
    }

    public function getOverallMetrics()
    {
        return [
            'total_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], []),
            'total_clients_count' => 
                ModelAggregatorService::getModelData(Client::class, 'count', ['clients'], []),
            'total_users_count' => 
                ModelAggregatorService::getModelData(User::class, 'count', ['users'], []),
            'total_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], []),
            'total_expenses_count' => 
                ModelAggregatorService::getModelData(Expenses::class, 'count', ['expenses'], []),
            'total_expenses_sum' => 
                ModelAggregatorService::getModelData(Expenses::class, 'sum', ['expenses'], [], [], 'price', fn ($value) => round($value, 2)),
            'total_gross_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [], [], 'price', fn ($value) => round($value, 2)),
            'total_net_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [], [], \DB::raw('price * (costs / 100.0)'), fn ($value) => round($value, 2)),
            'total_investments_sum' => 
                ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], [], 'amount', fn ($value) => round($value, 2)),
            'total_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], []),
            'total_active_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [], [['status_id', '1']]),
            'total_after_date_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [], [['status_id', '1'], ['date', '<', Carbon::today()->toDateString()]]),
            'total_completed_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [], [['status_id', '2']]),
            'total_awaiting_repayment_sum' => 
                ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], [['status_id', '1']], \DB::raw('(amount + amount * (interest_rate / 100.0)) - total_repayment'), fn ($value) => round($value, 2)),
            'total_awaiting_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['status_id', '1']]),
            'total_in_progress_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['status_id', '2']]),
            'total_after_deadline_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['deadline', '<', Carbon::today()->toDateString()], fn ($query) => $query->whereNull('end')]),
            'total_completed_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [], [['status_id', '3']]),
            'total_active_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], [], [['status_id', '1']]),
            'total_completed_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], [], [['status_id', '2']]),
            'total_awaiting_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [], [['status_id', '1']], \DB::raw('price * (costs / 100.0)'), fn ($value) => round($value, 2)),            
            'wallet' => self::getWallet(),
        ];
    }

    private function getWallet()
    {
        $total_net_income_sum = ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [], [['status_id', '2']], \DB::raw('price * (costs / 100.0)'), fn ($value) => round($value, 2));
        $total_expenses_sum = ModelAggregatorService::getModelData(Expenses::class, 'sum', ['expenses'], [], [], 'price', fn ($value) => round($value, 2));
        $total_investments_sum = ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], [], 'amount', fn ($value) => round($value, 2));
        $total_repayment_sum = ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], [], 'total_repayment', fn ($value) => round($value, 2));

        return round($total_net_income_sum - $total_expenses_sum + $total_investments_sum - $total_repayment_sum, 2);
    }
}

