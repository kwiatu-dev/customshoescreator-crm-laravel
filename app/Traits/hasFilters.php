<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Helpers\RequestProcessor;

trait HasFilters
{
    public function scopeFilter($query, Request $request)
    {
        $filters = RequestProcessor::getFilterFields($request, $this->filterable);
        $request->session()->put('filters', $filters);

        $query->when(
            $filters['deleted'] ?? false,
            fn ($query, $value) => $query->withTrashed()
        )->when(
            $filters['search'] ?? false,
            fn ($query, $value) => $query->where(
                function ($query) use ($value) {
                    foreach ($this->searchable as $column) {
                        $query->orWhere($column, 'like', "%$value%");
                    }
                }
            )
        );

        return $query;
    }
}