<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Project;
use App\Models\User;
use App\Services\Metrics;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    
    public function index(Request $request)
    {
        Cache::flush();
        $overall_metrics = null;
        $user_metrics = null;
    
        if ($request->user() && $request->user()?->is_admin) {
            $overall_metrics = Metrics::getOverallMetrics();
        }

        if ($request->user()) {
            $user_metrics = Metrics::getUserMetrics($request->user());
        }

        return inertia(
            'Dashboard/Index',
            [
                'overallMetrics' => $overall_metrics,
                'userMetrics' => $user_metrics,
            ]
        );
    }

    public function getMonthlyCompletedProjectsCount(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $year = (int) $request->input('year');
        $data = array_fill(0, 12, 0);

        $rows = DB::table('projects')
            ->selectRaw('MONTH(end) as month, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->whereYear('end', $year)
            ->groupBy(DB::raw('MONTH(end)'))
            ->get();

        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = (int) $row->count;
        }

        return response()->json([
            'labels' => $this->getPolishMonthLabels(),
            'data' => $data,
        ]);
    }

    public function getMonthlyNewProjectsCount(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $year = (int) $request->input('year');
        $data = array_fill(0, 12, 0);
    
        $rows = DB::table('projects')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
    
        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = (int) $row->count;
        }
    
        return response()->json([
            'labels' => $this->getPolishMonthLabels(),
            'data' => $data,
        ]);
    }

    public function monthlyFinancialStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $year = $request->input('year');

        $labels = $this->getPolishMonthLabels();
        $incomeData = $this->getMonthlyIncome($year);
        $costsData = $this->getMonthlyCosts($year);
        $netData = $this->calculateMonthlyNet($incomeData, $costsData);

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Przychód',
                    'data' => $incomeData,
                ],
                [
                    'label' => 'Wydatki',
                    'data' => $costsData,
                ],
                [
                    'label' => 'Dochód',
                    'data' => $netData,
                ],
            ],
        ]);
    }

    private function getPolishMonthLabels(): array
    {
        return [
            'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec',
            'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'
        ];
    }

    private function getMonthlyIncome(int $year): array
    {
        $data = array_fill(0, 12, 0);

        $rows = DB::table('incomes')
            ->selectRaw('MONTH(date) as month, SUM(price * costs / 100) as income')
            ->whereNull('deleted_at')
            ->whereYear('date', $year)
            ->where('status_id', 2)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = round((float) $row->income, 2);
        }

        return $data;
    }

    private function getMonthlyCosts(int $year): array
    {
        $data = array_fill(0, 12, 0);

        $rows = DB::table('expenses')
            ->selectRaw('MONTH(date) as month, SUM(price) as costs')
            ->whereNull('deleted_at')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        foreach ($rows as $row) {
            $index = (int) $row->month - 1;
            $data[$index] = round((float) $row->costs, 2);
        }

        return $data;
    }

    private function calculateMonthlyNet(array $income, array $costs): array
    {
        $profit = [];
        for ($i = 0; $i < 12; $i++) {
            $profit[] = round($income[$i] - $costs[$i], 2);
        }
        return $profit;
    }

    public function projectYears()
    {
        $years = DB::table('projects')
            ->whereNull('deleted_at')
            ->whereNotNull('created_at')
            ->select(DB::raw('DISTINCT YEAR(created_at) as year'))
            ->orderBy('year')
            ->pluck('year');

        return response()->json($years);
    }

    public function projectTypeBreakdown(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'sometimes|date_format:Y-m-d',
            'to'   => 'sometimes|date_format:Y-m-d|after_or_equal:from',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $from = $request->input('from');
        $to = $request->input('to');

        $query = DB::table('projects')
            ->select('type_id', DB::raw('COUNT(*) as count'))
            ->whereNull('deleted_at');

        if ($from) {
            $query->where('created_at', '>=', $from);
        }

        if ($to) {
            $query->where('created_at', '<=', $to);
        }

        $rawData = $query
            ->groupBy('type_id')
            ->get()
            ->keyBy('type_id');

        $labels = [
            1 => 'Renowacja butów',
            2 => 'Personalizacja butów',
            3 => 'Personalizacja ubrań',
            4 => 'Haft ręczny',
            5 => 'Haft komputerowy',
            6 => 'Inne',
        ];

        $result = [
            'labels' => array_values($labels),
            'data' => [],
        ];

        foreach ($labels as $typeId => $label) {
            $result['data'][] = isset($rawData[$typeId]) ? (int) $rawData[$typeId]->count : 0;
        }

        return response()->json($result);
    }

    public function topProjectsByIncome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'sometimes|integer|min:1',
            'from'  => 'sometimes|date_format:Y-m-d',
            'to'    => 'sometimes|date_format:Y-m-d|after_or_equal:from',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $limit = (int) $request->input('limit', 3);
        $from = $request->input('from'); 
        $to = $request->input('to');    

        $query = DB::table('projects')
            ->join('incomes', 'projects.id', '=', 'incomes.project_id')
            ->leftJoin('project_images', 'projects.id', '=', 'project_images.project_id')
            ->whereNull('projects.deleted_at')
            ->whereNull('incomes.deleted_at')
            ->where('incomes.status_id', 2); 

        if ($from) {
            $query->where('incomes.date', '>=', $from);
        }

        if ($to) {
            $query->where('incomes.date', '<=', $to);
        }

        $topProjects = $query
            ->select(
                'projects.id',
                'projects.title',
                DB::raw('incomes.price as total_income'),
                DB::raw('DATEDIFF(projects.end, projects.start) as duration_days'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(project_images.file ORDER BY RAND()), ",", 1) as preview_image')
            )
            ->groupBy('projects.id', 'projects.title', 'projects.start', 'projects.end', 'incomes.price')
            ->orderByDesc('total_income')
            ->limit($limit)
            ->get();

        $topProjects->transform(function ($project) {
            if (!empty($project->preview_image)) {
                $project->preview_image_url = route('private.files', [
                    'catalog' => 'projects',
                    'file' => $project->preview_image,
                ]);
            } else {
                $project->preview_image_url = null;
            }
            return $project;
        });

        return response()->json($topProjects);
    }

    public function topUsersByIncome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'sometimes|integer|min:1',
            'from'  => 'sometimes|date_format:Y-m-d',
            'to'    => 'sometimes|date_format:Y-m-d|after_or_equal:from',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $limit = (int) $request->input('limit', 3);
        $from = $request->input('from'); 
        $to = $request->input('to');    
    
        $query = DB::table('incomes')
            ->join('projects', 'incomes.project_id', '=', 'projects.id')
            ->join('users', 'projects.created_by_user_id', '=', 'users.id')
            ->whereNull('incomes.deleted_at')
            ->whereNull('projects.deleted_at')
            ->where('incomes.status_id', 2);
    
        if ($from) {
            $query->where('incomes.date', '>=', $from);
        }
    
        if ($to) {
            $query->where('incomes.date', '<=', $to);
        }
    
        $topUsers = $query
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"),
                DB::raw('SUM(incomes.price) as total_income'),
                DB::raw('COUNT(DISTINCT projects.id) as project_count'),
                DB::raw('ROUND(AVG(DATEDIFF(projects.end, projects.start)), 2) as avg_completion_days')
            )
            ->groupBy('users.id', 'users.first_name', 'users.last_name')
            ->orderByDesc('total_income')
            ->limit($limit)
            ->get();
    
        return response()->json($topUsers);
    }

    public function kpi(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json(['error' => 'Brak dostępu'], 403);
        }

        $validator = Validator::make($request->all(), [
            'from' => ['required', 'date_format:Y-m'],
            'to'   => ['required', 'date_format:Y-m', 'after_or_equal:from'],
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $fromInput = $request->input('from');
        $toInput = $request->input('to');
    
        $from = Carbon::createFromFormat('Y-m', $fromInput)->startOfMonth()->startOfDay();
        $to = Carbon::createFromFormat('Y-m', $toInput)->endOfMonth()->endOfDay();
    
        $kpi = $this->getKPI($from, $to);
    
        return response()->json($kpi);
    }

    private function getKPI($from, $to)
    {
        $previousPeriod = $this->getPreviousPeriod($from, $to);
        $currentStatus = $this->calculateStatsInPeriod($from, $to);
        $previousStats = $this->calculateStatsInPeriod($previousPeriod['from'], $previousPeriod['to']);

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
            'financial' => $this->formatFinancialKPI($currentStatus, $previousStats, $getPercentageChange),
            'projects' => $this->formatProjectKPI($currentStatus, $previousStats, $getPercentageChange),
            'clients' => $this->formatClientKPI($currentStatus, $previousStats, $getPercentageChange),
        ];
    }

    private function getPreviousPeriod($from, $to)
    {
        $previousFrom = Carbon::parse($from)->subDays(Carbon::parse($to)->diffInDays($from));
        $previousTo = Carbon::parse($from);

        return ['from' => $previousFrom, 'to' => $previousTo];
    }

    private function calculateStatsInPeriod($from, $to)
    {
        $income = Income::whereBetween('date', [$from, $to])->where('status_id', 2)->sum(\DB::raw('price'));
        $expenses = Expenses::whereBetween('date', [$from, $to])->sum('price');
        $profit = $income - $expenses;

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $profit,
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

    private function formatFinancialKPI($current, $previous, $getPercentageChange)
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

    private function formatProjectKPI($current, $previous, $getPercentageChange)
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
            'types' => $this->formatProjectTypesKPI($current, $previous, $getPercentageChange),
        ];
    }

    private function formatClientKPI($current, $previous, $getPercentageChange)
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

    private function formatProjectTypesKPI($current, $previous, $getPercentageChange)
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
