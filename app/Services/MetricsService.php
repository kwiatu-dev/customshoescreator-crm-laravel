<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\{User, Project, Client, Income, Expenses, Investment};


class Metrics
{
    private static function cacheTTL() {
        return now()->addHours(24);
    }

    public static function getUserMetrics($user_id)
    {
        return [
            'total_awaiting_projects_count' => self::getTotalAwaitingProjectsCount($user_id),
            'total_in_progress_projects_count' => self::getTotalInProgressProjectsCount($user_id),
            'total_after_deadline_projects_count' => self::getTotalAfterDeadlineProjectsCount($user_id),
            'total_completed_projects_count' => self::getTotalCompletedProjectsCount($user_id),
            'total_active_income_count' => self::getTotalRelatedActiveIncomeCount($user_id),
            'total_completed_income_count' => self::getTotalRelatedCompletedIncomeCount($user_id),
            'total_active_investments_count' => self::getTotalRelatedActiveInvestmentsCount($user_id),
            'total_after_date_investments_count' => self::getTotalRelatedAfterDateInvestmentsCount($user_id),
            'total_completed_investments_count' => self::getTotalRelatedCompletedInvestmentsCount($user_id),
            'total_investor_awaiting_repayment_sum' => self::getTotalInvestorAwaitingRepaymentSum($user_id),
            'total_user_awaiting_income_sum' => self::getTotalUserAwaitingIncomeSum($user_id),
        ];
    }

    public static function getOverallMetrics()
    {
        return [
            'total_projects_count' => self::getTotalProjectsCount(),
            'total_clients_count' => self::getTotalClientsCount(),
            'total_users_count' => self::getTotalUsersCount(),
            'total_income_count' => self::getTotalIncomeCount(),
            'total_expenses_count' => self::getTotalExpensesCount(),
            'total_expenses_sum' => self::getTotalExpensesSum(),
            'total_gross_income_sum' => self::getTotalGrossIncomeSum(),
            'total_net_income_sum' => self::getTotalNetIncomeSum(),
            'total_investments_sum' => self::getTotalInvestmentsSum(),
            'total_investments_count' => self::getTotalInvestmentsCount(),
            'total_active_investments_count' => self::getTotalActiveInvestmentsCount(),
            'total_after_date_investments_count' => self::getTotalAfterDateInvestmentsCount(),
            'total_completed_investments_count' => self::getTotalCompletedInvestmentsCount(),
            'total_awaiting_repayment_sum' => self::getTotalAwaitingRepaymentSum(),
            'total_awaiting_projects_count' => self::getTotalAwaitingProjectsCount(),
            'total_in_progress_projects_count' => self::getTotalInProgressProjectsCount(),
            'total_after_deadline_projects_count' => self::getTotalAfterDeadlineProjectsCount(),
            'total_completed_projects_count' => self::getTotalCompletedProjectsCount(),
            'total_active_income_count' => self::getTotalActiveIncomeCount(),
            'total_completed_income_count' => self::getTotalCompletedIncomeCount(),
            'total_awaiting_income_sum' => self::getTotalAwaitingIncomeSum(),
            'wallet' => self::getWallet(),
        ];
    }

    private static function getTotalProjectsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_projects_count');

        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Project::where('created_by_user_id', $user_id)->count()
                : Project::count()
        );
    }

    private static function getTotalClientsCount($user_id = null)
    {  
        $cacheTag = config('cache_tags.clients');
        $cacheKeyBase = config('cache_keys.total_clients_count');

        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';

        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Client::where('created_by_user_id', $user_id)->count()
                : Client::count()
        );
    }

    private static function getTotalUsersCount($user_id = null)
    {
        $cacheKeyBase = config('cache_keys.total_users_count');
        $cacheTag = config('cache_tags.users');

        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? User::where('created_by_user_id', $user_id)->count()
                : User::count()
        );
    }

    private static function getTotalIncomeCount($user_id = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_income_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Income::where('created_by_user_id', $user_id)
                    ->count()
                : Income::count()
        );
    }

    private static function getTotalExpensesCount($user_id = null)
    {
        $cacheTag = config('cache_tags.expenses');
        $cacheKeyBase = config('cache_keys.total_expenses_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Expenses::where('created_by_user_id', $user_id)->count()
                : Expenses::count()
        );
    }

    private static function getTotalExpensesSum($user_id = null)
    {
        $cacheTag = config('cache_tags.expenses');
        $cacheKeyBase = config('cache_keys.total_expenses_sum');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? round(Expenses::where('created_by_user_id', $user_id)->sum('price'), 2)
                : round(Expenses::sum('price'), 2)
        );
    }

    private static function getTotalGrossIncomeSum($user_id = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_gross_income_sum');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? round(Income::where('created_by_user_id', $user_id)
                    ->sum('price'), 2)
                : round(Income::sum('price'), 2)
        );
    }

    private static function getTotalNetIncomeSum($user_id = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_net_income_sum');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? round(Income::where('created_by_user_id', $user_id)
                    ->sum(\DB::raw('price * (costs / 100)'), 2))
                : round(Income::sum(\DB::raw('price * (costs / 100)')), 2)
        );
    }

    private static function getTotalUserAwaitingIncomeSum($user_id)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_user_awaiting_income_sum');
        $cacheKey = $cacheKeyBase . '_user_' . $user_id;
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            function () use ($user_id) {
                $incomes = Income::where('status_id', 1)
                    ->where(function ($query) use ($user_id) {
                        $query->whereHas('project', function ($query) use ($user_id) {
                            $query->where('created_by_user_id', $user_id);
                        })
                        ->orWhereRaw("JSON_EXTRACT(distribution, '$.\"{$user_id}\"') IS NOT NULL");
                    })
                    ->get();

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
            }
        );
    }

    private static function getTotalInvestmentsSum($user_id = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_investments_sum');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? round(Investment::where('created_by_user_id', $user_id)->sum('amount'), 2)
                : round(Investment::sum('amount'), 2)
        );
    }

    private static function getTotalInvestmentsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_investments_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Investment::where('created_by_user_id', $user_id)->count()
                : Investment::count()
        );
    }

    private static function getTotalAfterDateInvestmentsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_after_date_investments_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->count()
        );
    }

    private static function getTotalRelatedAfterDateInvestmentsCount($user_id)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_related_after_date_investments_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->where(function ($query) use ($user_id) {
                        $query->where('user_id', $user_id)
                        ->orWhere('created_by_user_id', $user_id);
                    })
                    ->count()
                : Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->count()
        );
    }

    private static function getTotalActiveInvestmentsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_active_investments_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Investment::where('status_id', '1')
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Investment::where('status_id', '1')
                    ->count()
        );
    }

    private static function getTotalRelatedActiveInvestmentsCount($user_id)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_related_active_investments_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Investment::where('status_id', '1')
                    ->where(function ($query) use ($user_id) {
                        $query->where('user_id', $user_id)
                        ->orWhere('created_by_user_id', $user_id);
                    })
                    ->count()
                : Investment::where('status_id', '1')
                    ->count()
        );
    }

    private static function getTotalCompletedInvestmentsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_completed_investments_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Investment::where('status_id', '2')
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Investment::where('status_id', '2')
                    ->count()
        );
    }

    private static function getTotalRelatedCompletedInvestmentsCount($user_id)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_related_completed_investments_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Investment::where('status_id', '2')
                    ->where(function ($query) use ($user_id) {
                        $query->where('user_id', $user_id)
                        ->orWhere('created_by_user_id', $user_id);
                    })
                    ->count()
                : Investment::where('status_id', '2')
                    ->count()
        );
    }

    private static function getTotalAwaitingRepaymentSum($user_id = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_awaiting_repayment_sum');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? round(Investment::where('created_by_user_id', $user_id)
                    ->sum(\DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment')), 2)
                : round(Investment::sum(\DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment')), 2)
        );
    }

    private static function getTotalInvestorAwaitingRepaymentSum($investor_id) {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_investor_awaiting_repayment_sum');
        $cacheKey = $cacheKeyBase . '_user_' . $investor_id;

        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => round(Investment::where('user_id', $investor_id)
                        ->sum(\DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment')), 2));
    }

    private static function getTotalAwaitingProjectsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_awaiting_projects_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Project::where('status_id', '1')
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Project::where('status_id', '1')
                    ->count()
        );
    }

    private static function getTotalInProgressProjectsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_in_progress_projects_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Project::where('status_id', '2')
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Project::where('status_id', '2')
                    ->count()
        );
    }

    private static function getTotalAfterDeadlineProjectsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_after_deadline_projects_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Project::whereNull('end')
                    ->where('deadline', '<', now())
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Project::whereNull('end')
                    ->where('deadline', '<', now())
                    ->count()
        );
    }

    private static function getTotalCompletedProjectsCount($user_id = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_completed_projects_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Project::where('status_id', '3')
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Project::where('status_id', '3')
                    ->count()
        );
    }

    private static function getTotalActiveIncomeCount($user_id = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_active_income_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Income::where('status_id', '1')
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Income::where('status_id', '1')->count()
        );
    }

    private static function getTotalRelatedActiveIncomeCount($user_id)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_related_active_income_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Income::where('status_id', '1')
                    ->where(function ($query) use ($user_id) {
                        $query->whereHas('project', function ($query) use ($user_id) {
                            $query->where('created_by_user_id', $user_id);
                        })
                        ->orWhere('created_by_user_id', $user_id)
                        ->orWhereRaw("JSON_EXTRACT(distribution, '$.{$user_id}') IS NOT NULL");
                    })
                    ->count()
                : Income::where('status_id', '1')->count()
        );
    }

    private static function getTotalCompletedIncomeCount($user_id = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_completed_income_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Income::where('status_id', '2')
                    ->where('created_by_user_id', $user_id)
                    ->count()
                : Income::where('status_id', '2')->count()
        );
    }

    private static function getTotalRelatedCompletedIncomeCount($user_id)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_related_completed_income_count');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user_id
                ? Income::where('status_id', '2')
                    ->where(function ($query) use ($user_id) {
                        $query->whereHas('project', function ($query) use ($user_id) {
                            $query->where('created_by_user_id', $user_id);
                        })
                        ->orWhere('created_by_user_id', $user_id)
                        ->orWhereRaw("JSON_EXTRACT(distribution, '$.{$user_id}') IS NOT NULL");
                    })
                    ->count()
                : Income::where('status_id', '2')->count()
        );
    }

    private static function getTotalAwaitingIncomeSum($user_id = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_awaiting_income_sum');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            function () use ($user_id) {
                $query = Income::where('status_id', '1');
    
                if ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                }
    
                return round($query->sum(\DB::raw('price * (costs / 100)')), 2);
            }
        );
    }

    private static function getTotalRepaymentSum($user_id = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_repayment_sum');
    
        $cacheKey = $user_id
            ? $cacheKeyBase . '_user_' . $user_id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => round(
                $user_id
                    ? Investment::where('created_by_user_id', $user_id)->sum('total_repayment')
                    : Investment::sum('total_repayment'),
                2
            )
        );
    }

    private static function getWallet()
    {
        $total_net_income_sum = self::getTotalNetIncomeSum();
        $total_expenses_sum = self::getTotalExpensesSum();
        $total_investments_sum = self::getTotalInvestmentsSum();
        $total_repayment_sum = self::getTotalRepaymentSum();

        return round($total_net_income_sum - $total_expenses_sum + $total_investments_sum - $total_repayment_sum, 2);
    }
}

