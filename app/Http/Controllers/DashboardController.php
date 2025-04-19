<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Project;
use App\Models\User;
use App\Services\CacheService;
use App\Services\StatsService;
use App\Services\MetricsService;
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
        $users = null;
    
        if ($request->user() && $request->user()->is_admin) {
            $users = User::query()->withTrashed()->get();
            $user_id = $request->input('user_id') ?? $request->user()->id;  
            $user_metrics = MetricsService::getUserMetrics($user_id);  
            $overall_metrics = MetricsService::getOverallMetrics();
        } 
        else {
            $user_metrics = MetricsService::getUserMetrics($request->user()->id);
        }

        return inertia(
            'Dashboard/Index',
            [
                'overallMetrics' => $overall_metrics,
                'userMetrics' => $user_metrics,
                'users' => $users,
            ]
        );
    }

    protected static array $validationRules = [
        'year' => 'required|integer|min:2000|max::dynamic_year',
        'user_id' => 'nullable|integer|exists:users,id',
        'month' => 'required|integer|min:1|max:12',
        'limit' => 'nullable|integer|min:1',
        'from'  => 'nullable|date_format:Y-m-d',
        'to'    => 'nullable|date_format:Y-m-d|after_or_equal:from',
    ];

    protected function getValidationRules(array $fields, array $overrideRules = []): array
    {
        return collect(static::$validationRules)
            ->only($fields)
            ->merge($overrideRules)
            ->map(function ($rule, $key) {
                if (str_contains($rule, ':dynamic_year')) {
                    return str_replace(':dynamic_year', date('Y'), $rule);
                }
                return $rule;
            })
            ->toArray();
    }

    protected function validateInput(Request $request, array $fields, array $overrideRules = [], string $source = 'all'): array
    {
        $data = match ($source) {
            'query' => $request->query(),
            'post' => $request->post(),
            default => $request->all(),
        };
    
        $rules = $this->getValidationRules($fields, $overrideRules);
    
        return Validator::make($data, $rules)->validate();
    }

    protected function authorizeUserAccess(?int $user_id): void
    {
        if (!Auth::user()->is_admin && $user_id !== null && $user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view these stats');
        }
    }

    public function getMonthlyCompletedProjectsCount(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $this->validateInput($request, ['year', 'user_id']);
        $year = (int) $validated['year'];
        $user_id = $validated['user_id'] ?? null;
    
        $this->authorizeUserAccess($user_id);
        
        $data = CacheService::remember(['projects'], ['year' => $year, 'user_id' => $user_id], fn () => StatsService::monthlyCompletedProjectsCount($year, $user_id));

        return response()->json($data);
    }

    public function getMonthlyNewProjectsCount(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $this->validateInput($request, ['year', 'user_id']);
        $year = (int) $validated['year'];
        $user_id = $validated['user_id'] ?? null;
    
        $this->authorizeUserAccess($user_id);
        
        $data = CacheService::remember(['projects'], ['year' => $year, 'user_id' => $user_id], fn () => StatsService::monthlyNewProjectsCount($year, $user_id));
    
        return response()->json($data);
    }

    public function getMonthlyFinancialStats(Request $request)
    {
        $validated = $this->validateInput($request, ['year', 'user_id']);
        $year = (int) $validated['year'];
        $user_id = $validated['user_id'] ?? null;
    
        $this->authorizeUserAccess($user_id);

        $data = CacheService::remember(['incomes', 'projects', 'expenses'], ['year' => $year, 'user_id' => $user_id], fn () => StatsService::monthlyFinancialStats($year, $user_id));

        return response()->json($data);
    }

    public function getProjectYears(Request $request)
    {
        $validated = $this->validateInput($request, ['user_id']);
        $user_id = $validated['user_id'] ?? null;
    
        $this->authorizeUserAccess($user_id);

        $years = CacheService::remember(['projects'], ['user_id' => $user_id], fn () => StatsService::projectYears($user_id));

        return response()->json($years);
    }

    public function getIncomeYears(Request $request) {
        $validated = $this->validateInput($request, ['user_id']);
        $user_id = $validated['user_id'] ?? null;
    
        $this->authorizeUserAccess($user_id);

        $years = CacheService::remember(['projects', 'incomes'], ['user_id' => $user_id], fn () => StatsService::incomeYears($user_id));

        return response()->json($years);
    }

    public function getProjectTypeBreakdown(Request $request)
    {
        $validated = $this->validateInput($request, ['year', 'user_id']);
        $year = (int) $validated['year'];
        $user_id = $validated['user_id'] ?? null;
    
        $this->authorizeUserAccess($user_id);

        $result = CacheService::remember(['projects'], ['year' => $year, 'user_id' => $user_id], fn () => StatsService::projectTypeBreakdown($year, $user_id));

        return response()->json($result);
    }

    public function getTopProjectsByIncome(Request $request)
    {
        $validated = $this->validateInput($request, ['limit', 'from', 'to']);
        $limit = $validated['limit'] ? (int) $validated['limit'] : 3;
        $from = $validated['from'] ?? null; 
        $to = $validated['to'] ?? null;   

        $data = CacheService::remember(['projects', 'incomes'], ['limit' => $limit, 'from' => $from, 'to' => $to], fn () => StatsService::topProjectsByIncome($limit, $from, $to));

        return response()->json($data);
    }

    public function topUsersByIncome(Request $request)
    {
        $validated = $this->validateInput($request, ['limit', 'from', 'to']);
        $limit = $validated['limit'] ? (int) $validated['limit'] : 3;
        $from = $validated['from'] ?? null; 
        $to = $validated['to'] ?? null;   
    
        $data = CacheService::remember(['projects', 'incomes'], ['limit' => $limit, 'from' => $from, 'to' => $to], fn () => StatsService::topUsersByIncome($limit, $from, $to));
    
        return response()->json($data);
    }

    public function getKpi(Request $request)
    {
        $validated = $this->validateInput($request, ['from', 'to', 'user_id'], [
            'from'  => 'required|date_format:Y-m-d',
            'to'    => 'required|date_format:Y-m-d|after_or_equal:from',
        ]);

        $user_id = $validated['user_id'] ?? null;
        $fromInput = $validated['from'] ?? null;
        $toInput = $validated['to'] ?? null;
    
        $this->authorizeUserAccess($user_id);
    

    
        return response()->json($kpi);
    }
}
