<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\{User, Project, Client, Income, Expenses, Investment};
use Closure;
use DB;

class MetricsService
{
    public static function getUserMetrics($user_id)
    {
        $relatedIncomeScope = fn ($query) => 
            $query->where(function ($query) use ($user_id) {
                $query->whereHas('project', function ($query) use ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                })
                ->orWhere('created_by_user_id', $user_id)
                ->orWhereRaw("JSON_EXTRACT(distribution, '$.{$user_id}') IS NOT NULL");
            });

        $relatedInvestmentScope = fn ($query) => 
            $query->where(function ($query) use ($user_id) {
                $query
                    ->where('user_id', $user_id)
                    ->orWhere('created_by_user_id', $user_id);
            });

        $userHasIncomeScope = fn ($query) => 
            $query->where(function ($query) use ($user_id) {
                $query->whereHas('project', function ($query) use ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                })
                ->orWhereRaw("JSON_EXTRACT(distribution, '$.\"{$user_id}\"') IS NOT NULL");
            });

        $computeUserEarnings = function ($incomes) use ($user_id) {
            $total = 0;

            foreach ($incomes as $income) {
                $price = (float) $income->price;
                $costs = (float) $income->costs;
                $commission = (float) $income->commission;
                $creator = 0;
                $participant = 0;
        
                $base = $price - ($price * ($costs / 100));
                
                if ($commission) {
                    $creator = round($base * ($commission / 100), 2);
                }

                if (is_array($income->distribution) && array_key_exists($user_id, $income->distribution)) {
                    $participant = round(($base - $creator) * (((int) $income->distribution[$user_id] ?? 0) / 100), 2);

                }
                    
                $total += $creator + $participant;
            }
            
            return round($total, 2);
        };

        return [
            'total_awaiting_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['status_id', '1'], ['created_by_user_id', $user_id]]),
            'total_in_progress_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['status_id', '2'], ['created_by_user_id', $user_id]]),
            'total_after_deadline_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['deadline', '<', now()], ['created_by_user_id', $user_id], fn ($query) => $query->whereNull('end')]),
            'total_completed_projects_count' =>  
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['status_id', '3'], ['created_by_user_id', $user_id]]),
            'total_active_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], [['status_id', '1'], $relatedIncomeScope]),
            'total_completed_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], [['status_id', '2'], $relatedIncomeScope]),
            'total_active_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [['status_id', '1'], $relatedInvestmentScope]),
            'total_after_date_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [['status_id', '1'], ['date', '<', now()], $relatedInvestmentScope]),
            'total_completed_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [['status_id', '2'], $relatedInvestmentScope]),
            'total_investor_awaiting_repayment_sum' => 
                ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [['status_id', '1'], ['user_id', $user_id]], \DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment'), fn ($value) => round($value, 2)),
            'total_user_awaiting_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'get', ['incomes'], [['status_id', '1'], $userHasIncomeScope], null, $computeUserEarnings),
        ];
    }



    public static function getOverallMetrics()
    {
        return [
            'total_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects']),
            'total_clients_count' => 
                ModelAggregatorService::getModelData(Client::class, 'count', ['clients']),
            'total_users_count' => 
                ModelAggregatorService::getModelData(User::class, 'count', ['users']),
            'total_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes']),
            'total_expenses_count' => 
                ModelAggregatorService::getModelData(Expenses::class, 'count', ['expenses']),
            'total_expenses_sum' => 
                ModelAggregatorService::getModelData(Expenses::class, 'sum', ['expenses'], [], 'price', fn ($value) => round($value, 2)),
            'total_gross_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [], 'price', fn ($value) => round($value, 2)),
            'total_net_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [], \DB::raw('price * (costs / 100)'), fn ($value) => round($value, 2)),
            'total_investments_sum' => 
                ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], 'amount', fn ($value) => round($value, 2)),
            'total_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments']),
            'total_active_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [['status_id', '1']]),
            'total_after_date_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [['status_id', '1'], ['date', '<', now()]]),
            'total_completed_investments_count' => 
                ModelAggregatorService::getModelData(Investment::class, 'count', ['investments'], [['status_id', '2']]),
            'total_awaiting_repayment_sum' => 
                ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [['status_id', '1']], \DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment'), fn ($value) => round($value, 2)),
            'total_awaiting_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['status_id', '1']]),
            'total_in_progress_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['status_id', '2']]),
            'total_after_deadline_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['deadline', '<', now()], fn ($query) => $query->whereNull('end')]),
            'total_completed_projects_count' => 
                ModelAggregatorService::getModelData(Project::class, 'count', ['projects'], [['status_id', '3']]),
            'total_active_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], [['status_id', '1']]),
            'total_completed_income_count' => 
                ModelAggregatorService::getModelData(Income::class, 'count', ['incomes'], [['status_id', '2']]),
            'total_awaiting_income_sum' => 
                ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [['status_id', '1']], \DB::raw('price * (costs / 100)'), fn ($value) => round($value, 2)),            
            'wallet' => self::getWallet(),
        ];
    }

    private static function getWallet()
    {
        $total_net_income_sum = ModelAggregatorService::getModelData(Income::class, 'sum', ['incomes'], [['status_id', '2']], \DB::raw('price * (costs / 100)'), fn ($value) => round($value, 2));
        $total_expenses_sum = ModelAggregatorService::getModelData(Expenses::class, 'sum', ['expenses'], [], 'price', fn ($value) => round($value, 2));
        $total_investments_sum = ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], 'amount', fn ($value) => round($value, 2));
        $total_repayment_sum = ModelAggregatorService::getModelData(Investment::class, 'sum', ['investments'], [], 'total_repayment', fn ($value) => round($value, 2));

        return round($total_net_income_sum - $total_expenses_sum + $total_investments_sum - $total_repayment_sum, 2);
    }
}

