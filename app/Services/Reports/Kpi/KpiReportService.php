<?php
namespace App\Services;

class KpiReportService {
    public static function kpi($from, $to, $user_id) {
        $from = Carbon::createFromFormat('Y-m', $from)->startOfMonth()->startOfDay();
        $to = Carbon::createFromFormat('Y-m', $to)->endOfMonth()->endOfDay();
    
        return self::buildKpi($from, $to, $user_id);
    }

    private static function buildKpi($from, $to, $user_id) {
        $previousPeriod = self::getPreviousPeriod($from, $to);
        $currentStatus = self::calculateStatsInPeriod($from, $to, $user_id);
        $previousStats = self::calculateStatsInPeriod($previousPeriod['from'], $previousPeriod['to'], $user_id);

        $getPercentageChange = function ($current, $previous) {
            if ($previous == 0) {
                if ($current == 0) {
                    return 0;
                }
                return $current > 0 ? 100 : -100;
            }
            
            $change = (($current - $previous) / abs($previous)) * 100;
            return (int) floor(abs($change));
        };

        return [
            'financial' => self::formatFinancialKPI($currentStatus, $previousStats, $getPercentageChange),
            'projects' => self::formatProjectKPI($currentStatus, $previousStats, $getPercentageChange),
            'clients' => self::formatClientKPI($currentStatus, $previousStats, $getPercentageChange),
        ];
    }

    private static function getPreviousPeriod($from, $to)
    {
        $previousFrom = Carbon::parse($from)->subDays(Carbon::parse($to)->diffInDays($from));
        $previousTo = Carbon::parse($from);

        return ['from' => $previousFrom, 'to' => $previousTo];
    }

    private static function calculateStatsInPeriod($from, $to, $user_id)
    {
        $auth = Auth::user();
        $income = Income::whereBetween('date', [$from, $to])->where('status_id', 2)->sum(\DB::raw('price'));
        $expenses = Expenses::whereBetween('date', [$from, $to])->sum('price');
        $profit = $income - $expenses;

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $profit,
            'earnings' => null,
            'new_projects' => Project::whereBetween('created_at', [$from, $to])->count(),
            'completed_projects' => Project::whereBetween('end', [$from, $to])->count(),
            'avg_days_projects' => round(Project::whereBetween('end', [$from, $to])->avg(\DB::raw('DATEDIFF(end, start)')), 2),
            'new_clients' => Client::whereBetween('created_at', [$from, $to])->count(),
            
            'returning_clients' => Client::whereHas('projects', function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, $to]);
            })->whereHas('projects', function ($query) use ($from) {
                $query->where('end', '<', $from);
            })->count(),

            'project_type:renowacja-butow' => Project::where('type_id', '1')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:personalizacja-butow' => Project::where('type_id', '2')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:personalizacja-ubran' => Project::where('type_id', '3')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:haft-reczny' => Project::where('type_id', '4')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:haft-komputerowy' => Project::where('type_id', '5')->whereBetween('created_at', [$from, $to])->count(),
            'project_type:inne' => Project::where('type_id', '6')->whereBetween('created_at', [$from, $to])->count(),
        ];
    }

    private static function formatFinancialKPI($current, $previous, $getPercentageChange)
    {
        return [
            'income' => [
                'value' => round($current['income'], 2),
                'arrow' => $current['income'] >= $previous['income'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['income'], $previous['income']),
            ],
            'expenses' => [
                'value' => round($current['expenses'], 2),
                'arrow' => $current['expenses'] >= $previous['expenses'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['expenses'], $previous['expenses']),
            ],
            'profit' => [
                'value' => round($current['profit'], 2),
                'arrow' => $current['profit'] >= $previous['profit'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['profit'], $previous['profit']),
            ],
        ];
    }

    private static function formatProjectKPI($current, $previous, $getPercentageChange)
    {
        return [
            'new' => [
                'value' => $current['new_projects'],
                'arrow' => $current['new_projects'] >= $previous['new_projects'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['new_projects'], $previous['new_projects']),
            ],
            'completed' => [
                'value' => $current['completed_projects'],
                'arrow' => $current['completed_projects'] >= $previous['completed_projects'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['completed_projects'], $previous['completed_projects']),
            ],
            'avg_days' => [
                'value' => $current['avg_days_projects'],
                'arrow' => $current['avg_days_projects'] >= $previous['avg_days_projects'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['avg_days_projects'], $previous['avg_days_projects']),
            ],
            'types' => self::formatProjectTypesKPI($current, $previous, $getPercentageChange),
        ];
    }

    private static function formatClientKPI($current, $previous, $getPercentageChange)
    {
        return [
            'new' => [
                'value' => $current['new_clients'],
                'arrow' => $current['new_clients'] >= $previous['new_clients'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['new_clients'], $previous['new_clients']),
            ],
            'returning' => [
                'value' => $current['returning_clients'],
                'arrow' => $current['returning_clients'] >= $previous['returning_clients'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['returning_clients'], $previous['returning_clients']),
            ],
        ];
    }

    private static function formatProjectTypesKPI($current, $previous, $getPercentageChange)
    {
        return [
            'renowacja-butow' => [
                'value' => $current['project_type:renowacja-butow'],
                'arrow' => $current['project_type:renowacja-butow'] >= $previous['project_type:renowacja-butow'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:renowacja-butow'], $previous['project_type:renowacja-butow']),
            ],
            'personalizacja-butow' => [
                'value' => $current['project_type:personalizacja-butow'],
                'arrow' => $current['project_type:personalizacja-butow'] >= $previous['project_type:personalizacja-butow'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:personalizacja-butow'], $previous['project_type:personalizacja-butow']),
            ],
            'personalizacja-ubran' => [
                'value' => $current['project_type:personalizacja-ubran'],
                'arrow' => $current['project_type:personalizacja-ubran'] >= $previous['project_type:personalizacja-ubran'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:personalizacja-ubran'], $previous['project_type:personalizacja-ubran']),
            ],
            'haft-reczny' => [
                'value' => $current['project_type:haft-reczny'],
                'arrow' => $current['project_type:haft-reczny'] >= $previous['project_type:haft-reczny'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:haft-reczny'], $previous['project_type:haft-reczny']),
            ],
            'haft-komputerowy' => [
                'value' => $current['project_type:haft-komputerowy'],
                'arrow' => $current['project_type:haft-komputerowy'] >= $previous['project_type:haft-komputerowy'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:haft-komputerowy'], $previous['project_type:haft-komputerowy']),
            ],
            'inne' => [
                'value' => $current['project_type:inne'],
                'arrow' => $current['project_type:inne'] >= $previous['project_type:inne'] ? 'up' : 'down',
                'percentage' => $getPercentageChange($current['project_type:inne'], $previous['project_type:inne']),
            ],
        ];
    }
}