<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Helpers\RequestProcessor;
use Illuminate\Support\Facades\Auth;

trait HasFilters
{
    //aktualnie nie można filtrować danych relacyjnych typu (date, number, dictionary)
    public function scopeFilter($query, Request $request, bool $store_in_session = true)
    {
        $filters = RequestProcessor::getFilterFields($request, $this->filterable);

        if ($store_in_session) {
            $request->session()->put('filters', $filters);
        }

        $table = $query->getModel()->getTable();

        /**
         * Helper do bezpiecznego kwalifikowania kolumn
         */
        $qualify = function (string $column) use ($table) {
            return str_contains($column, '.')
                ? $column
                : $table . '.' . $column;
        };

        $query->when(
            $filters['search'] ?? false,
            function ($query, $value) use ($qualify) {
                $query->where(function ($query) use ($value, $qualify) {
                    foreach ($this->searchable as $field => $columns) {

                        if (is_array($columns)) {
                            $columns = array_map(
                                fn ($column) => $qualify($field . '.' . $column),
                                $columns
                            );

                            $query->orWhereRaw(
                                'CONCAT_WS(\' \', ' . implode(', ', $columns) . ') LIKE ?',
                                ['%' . $value . '%']
                            );
                        }
                        elseif (str_contains($columns, '.')) {
                            $query->orWhere($columns, 'like', "%{$value}%");
                        }
                        else {
                            $query->orWhere(
                                $qualify($columns),
                                'like',
                                "%{$value}%"
                            );
                        }
                    }
                });
            }
        );

        //dd($query->toSql());

        $query->when(
            $filters['deleted'] ?? false,
            fn ($query) => $query->withTrashed()
        );

        $applyRangeFilter = function ($query, $fields) use ($filters, $qualify) {
            foreach ($fields as $field => $type) {
                if (!array_key_exists($field, $filters)) {
                    continue;
                }

                if (str_ends_with($field, '_start')) {
                    $query->where(
                        $qualify(str_replace('_start', '', $field)),
                        '>=',
                        $filters[$field]
                    );
                }

                if (str_ends_with($field, '_end')) {
                    $query->where(
                        $qualify(str_replace('_end', '', $field)),
                        '<=',
                        $filters[$field]
                    );
                }
            }
        };

        $query->when(
            $this->filterable['date'] ?? false,
            fn ($query, $value) => $applyRangeFilter($query, $value)
        );

        $query->when(
            $this->filterable['dates'] ?? false,
            function ($query, $value) use ($applyRangeFilter) {
                foreach ($value as $group) {
                    $applyRangeFilter($query, $group);
                }
            }
        );

        $query->when(
            $this->filterable['number'] ?? false,
            fn ($query, $value) => $applyRangeFilter($query, $value)
        );

        $query->when(
            $this->filterable['numbers'] ?? false,
            function ($query, $value) use ($applyRangeFilter) {
                foreach ($value as $group) {
                    $applyRangeFilter($query, $group);
                }
            }
        );

        $query->when(
            $this->filterable['dictionary'] ?? false,
            function ($query, $value) use ($filters, $qualify) {
                foreach ($value as $group) {
                    foreach ($group as $field => $type) {
                        if (!array_key_exists($field, $filters)) {
                            continue;
                        }

                        $query->where(
                            $qualify($field),
                            $filters[$field]
                        );
                    }
                }
            }
        );

        $query->when(
            $filters['created_by_user'] ?? false,
            fn ($query) =>
                $query->where(
                    $qualify('created_by_user_id'),
                    Auth::id()
                )
        );

        if (method_exists($this, 'scopeUseModelFilters')) {
            $query->useModelFilters($filters);
        }

        return $query;
    }
}