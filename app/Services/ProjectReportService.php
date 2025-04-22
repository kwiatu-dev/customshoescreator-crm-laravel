<?php 
namespace App\Services;

use App\Models\Income;
use App\Models\Project;
use DB;

class ProjectReportService {
    public function getProjectYears($user_id = null) {
        $years = Project::query()
        ->when($user_id, function ($query, $user_id) {
            return $query->where('created_by_user_id', $user_id);
        })
        ->distinct()
        ->selectRaw('YEAR(created_at) as year')
        ->orderBy('year')
        ->pluck('year');

        return $years;
    }

    public function getProjectTypeBreakdown($year, $user_id = null) {
        $types = DB::table('project_types')
            ->pluck('name', 'id');

        $query = DB::table('projects')
            ->select('type_id', DB::raw('COUNT(*) as count'))
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)
            ->when($user_id, function ($query, $user_id) {
                return $query->where('created_by_user_id', $user_id);
            });

        $rawData = $query->groupBy('type_id')->get()->keyBy('type_id');

        $result = [
            'labels' => [],
            'data' => [],
        ];

        foreach ($types as $typeId => $typeName) {
            $result['labels'][] = $typeName;
            $result['data'][] = isset($rawData[$typeId]) ? (int) $rawData[$typeId]->count : 0;
        }

        return $result;
    }

    public function getMonthlyNewProjectsCount($year, $user_id = null): array {
        $data = ChartHelper::getMonthlyAggregatedData('projects', 'created_at', $year, null, 
            function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                }
            }
        );
    
        return [
            'labels' => ChartHelper::getMonthLabels(),
            'data' => $data,
            'user_id' => $user_id
        ];
    }

    public function getMonthlyCompletedProjectsCount($year, $user_id = null) {
        $data = ChartHelper::getMonthlyAggregatedData('projects', 'end', $year, null, 
            function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('created_by_user_id', $user_id);
                }
            }
        );

        return [
            'labels' => ChartHelper::getMonthLabels(),
            'data' => $data,
            'user_id' => $user_id
        ];
    }

    public static function getTopProjectsByIncome($limit, $from, $to) {
        $projects = Project::query()
            ->with('previewImage')
            ->whereNull('deleted_at')
            ->whereNotNull('end')
            ->whereHas('incomes', function ($query) use ($from, $to) {
                $query->where('status_id', Income::STATUS_SETTLED)
                    ->whereNull('deleted_at');

                if (!is_null($from)) {
                    $query->where('date', '>=', $from);
                }

                if (!is_null($to)) {
                    $query->where('date', '<=', $to);
                }
            })
            ->withSum(['incomes as total_income' => function ($query) use ($from, $to) {
                $query->where('status_id', Income::STATUS_SETTLED)
                    ->whereNull('deleted_at');

                if (!is_null($from)) {
                    $query->where('date', '>=', $from);
                }

                if (!is_null($to)) {
                    $query->where('date', '<=', $to);
                }
            }], 'price')
            ->orderByDesc('total_income')
            ->limit($limit)
            ->get();

        return $projects->map(fn($project) => [
            'id' => $project->id,
            'title' => $project->title,
            'total_income' => $project->total_income,
            'duration_days' => $project->start && $project->end ? $project->end->diffInDays($project->start) : null,
            'preview_image_url' => $project->previewImage
                ? route('private.files', ['catalog' => 'projects', 'file' => $project->previewImage->file])
                : null,
        ]);
    }
}