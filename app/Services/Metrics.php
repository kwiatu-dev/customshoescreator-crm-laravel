<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\{User, Project, Client, Income, Expenses, Investment};


class Metrics
{
    private static function cacheTTL() {
        return now()->addHours(24);
    }

    public static function getUserMetrics(?User $user = null)
    {
        return [
            'total_awaiting_projects_count' => self::getTotalAwaitingProjectsCount($user),
            'total_in_progress_projects_count' => self::getTotalInProgressProjectsCount($user),
            'total_after_deadline_projects_count' => self::getTotalAfterDeadlineProjectsCount($user),
            'total_completed_projects_count' => self::getTotalCompletedProjectsCount($user),
            'total_active_income_count' => self::getTotalRelatedActiveIncomeCount($user),
            'total_completed_income_count' => self::getTotalRelatedCompletedIncomeCount($user),
            'total_active_investments_count' => self::getTotalRelatedActiveInvestmentsCount($user),
            'total_after_date_investments_count' => self::getTotalRelatedAfterDateInvestmentsCount($user),
            'total_completed_investments_count' => self::getTotalRelatedCompletedInvestmentsCount($user),

            // 'total_projects_count' => self::getTotalProjectsCount(),
            // 'total_clients_count' => self::getTotalClientsCount(),
            // 'total_users_count' => self::getTotalUsersCount(),
            // 'total_income_count' => self::getTotalIncomeCount(),
            // 'total_expenses_count' => self::getTotalExpensesCount(),
            // 'total_expenses_sum' => self::getTotalExpensesSum(),
            // 'total_gross_income_sum' => self::getTotalGrossIncomeSum(),
            // 'total_net_income_sum' => self::getTotalNetIncomeSum(),
            // 'total_investments_sum' => self::getTotalInvestmentsSum(),

            // 'total_awaiting_repayment_sum' => self::getTotalAwaitingRepaymentSum(),



            // 'total_awaiting_income_sum' => self::getTotalAwaitingIncomeSum(),
            // 'wallet' => self::getWallet(),
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

    private static function getTotalProjectsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_projects_count');

        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Project::where('created_by_user_id', $user->id)->count()
                : Project::count()
        );
    }

    private static function getTotalClientsCount(?User $user = null)
    {  
        $cacheTag = config('cache_tags.clients');
        $cacheKeyBase = config('cache_keys.total_clients_count');

        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';

        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Client::where('created_by_user_id', $user->id)->count()
                : Client::count()
        );
    }

    private static function getTotalUsersCount(?User $user = null)
    {
        $cacheKeyBase = config('cache_keys.total_users_count');
        $cacheTag = config('cache_tags.users');

        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? User::where('created_by_user_id', $user->id)->count()
                : User::count()
        );
    }

    private static function getTotalIncomeCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_income_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Income::where('created_by_user_id', $user->id)
                    ->count()
                : Income::count()
        );
    }

    private static function getTotalExpensesCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.expenses');
        $cacheKeyBase = config('cache_keys.total_expenses_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Expenses::where('created_by_user_id', $user->id)->count()
                : Expenses::count()
        );
    }

    private static function getTotalExpensesSum(?User $user = null)
    {
        $cacheTag = config('cache_tags.expenses');
        $cacheKeyBase = config('cache_keys.total_expenses_sum');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? round(Expenses::where('created_by_user_id', $user->id)->sum('price'), 2)
                : round(Expenses::sum('price'), 2)
        );
    }

    private static function getTotalGrossIncomeSum(?User $user = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_gross_income_sum');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? round(Income::where('created_by_user_id', $user->id)
                    ->sum('price'), 2)
                : round(Income::sum('price'), 2)
        );
    }

    private static function getTotalNetIncomeSum(?User $user = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_net_income_sum');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? round(Income::where('created_by_user_id', $user->id)
                    ->sum(\DB::raw('price * (costs / 100)'), 2))
                : round(Income::sum(\DB::raw('price * (costs / 100)')), 2)
        );
    }

    private static function getTotalInvestmentsSum(?User $user = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_investments_sum');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? round(Investment::where('created_by_user_id', $user->id)->sum('amount'), 2)
                : round(Investment::sum('amount'), 2)
        );
    }

    private static function getTotalInvestmentsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_investments_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Investment::where('created_by_user_id', $user->id)->count()
                : Investment::count()
        );
    }

    private static function getTotalAfterDateInvestmentsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_after_date_investments_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->count()
        );
    }

    private static function getTotalRelatedAfterDateInvestmentsCount(User $user)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_related_after_date_investments_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                        ->orWhere('created_by_user_id', $user->id);
                    })
                    ->count()
                : Investment::where('status_id', '1')
                    ->where('date', '<', now())
                    ->count()
        );
    }

    private static function getTotalActiveInvestmentsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_active_investments_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Investment::where('status_id', '1')
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Investment::where('status_id', '1')
                    ->count()
        );
    }

    private static function getTotalRelatedActiveInvestmentsCount(User $user)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_related_active_investments_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Investment::where('status_id', '1')
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                        ->orWhere('created_by_user_id', $user->id);
                    })
                    ->count()
                : Investment::where('status_id', '1')
                    ->count()
        );
    }

    private static function getTotalCompletedInvestmentsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_completed_investments_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Investment::where('status_id', '2')
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Investment::where('status_id', '2')
                    ->count()
        );
    }

    private static function getTotalRelatedCompletedInvestmentsCount(User $user)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_related_completed_investments_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Investment::where('status_id', '2')
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                        ->orWhere('created_by_user_id', $user->id);
                    })
                    ->count()
                : Investment::where('status_id', '2')
                    ->count()
        );
    }

    private static function getTotalAwaitingRepaymentSum(?User $user = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_awaiting_repayment_sum');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? round(Investment::where('created_by_user_id', $user->id)
                    ->sum(\DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment')), 2)
                : round(Investment::sum(\DB::raw('(amount + amount * (interest_rate / 100)) - total_repayment')), 2)
        );
    }

    private static function getTotalAwaitingProjectsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_awaiting_projects_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Project::where('status_id', '1')
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Project::where('status_id', '1')
                    ->count()
        );
    }

    private static function getTotalInProgressProjectsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_in_progress_projects_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Project::where('status_id', '2')
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Project::where('status_id', '2')
                    ->count()
        );
    }

    private static function getTotalAfterDeadlineProjectsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_after_deadline_projects_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Project::whereNull('end')
                    ->where('deadline', '<', now())
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Project::whereNull('end')
                    ->where('deadline', '<', now())
                    ->count()
        );
    }

    private static function getTotalCompletedProjectsCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.projects');
        $cacheKeyBase = config('cache_keys.total_completed_projects_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Project::where('status_id', '3')
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Project::where('status_id', '3')
                    ->count()
        );
    }

    private static function getTotalActiveIncomeCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_active_income_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Income::where('status_id', '1')
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Income::where('status_id', '1')->count()
        );
    }

    private static function getTotalRelatedActiveIncomeCount(User $user)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_related_active_income_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Income::where('status_id', '1')
                    ->where(function ($query) use ($user) {
                        $query->whereHas('project', function ($query) use ($user) {
                            $query->where('created_by_user_id', $user->id);
                        })
                        ->orWhere('created_by_user_id', $user->id);
                    })
                    ->count()
                : Income::where('status_id', '1')->count()
        );
    }

    private static function getTotalCompletedIncomeCount(?User $user = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_completed_income_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Income::where('status_id', '2')
                    ->where('created_by_user_id', $user->id)
                    ->count()
                : Income::where('status_id', '2')->count()
        );
    }

    private static function getTotalRelatedCompletedIncomeCount(User $user)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_related_completed_income_count');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => $user
                ? Income::where('status_id', '2')
                    ->where(function ($query) use ($user) {
                        $query->whereHas('project', function ($query) use ($user) {
                            $query->where('created_by_user_id', $user->id);
                        })
                        ->orWhere('created_by_user_id', $user->id);
                    })
                    ->count()
                : Income::where('status_id', '2')->count()
        );
    }

    private static function getTotalAwaitingIncomeSum(?User $user = null)
    {
        $cacheTag = config('cache_tags.incomes');
        $cacheKeyBase = config('cache_keys.total_awaiting_income_sum');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            function () use ($user) {
                $query = Income::where('status_id', '1');
    
                if ($user) {
                    $query->where('created_by_user_id', $user->id);
                }
    
                return round($query->sum(\DB::raw('price * (costs / 100)')), 2);
            }
        );
    }

    private static function getTotalRepaymentSum(?User $user = null)
    {
        $cacheTag = config('cache_tags.investments');
        $cacheKeyBase = config('cache_keys.total_repayment_sum');
    
        $cacheKey = $user
            ? $cacheKeyBase . '_user_' . $user->id
            : $cacheKeyBase . '_all';
    
        return Cache::tags([$cacheTag])->remember(
            $cacheKey,
            self::cacheTTL(),
            fn () => round(
                $user
                    ? Investment::where('created_by_user_id', $user->id)->sum('total_repayment')
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

