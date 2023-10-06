<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Helpers\RequestProcessor;

trait HasSorting
{
    public function scopeSort($query, Request $request)
    {
        $sort = RequestProcessor::getSortFields($request, $this->sortable);
        $request->session()->put('sort', $sort);

        $query->when(
            $sort ?? false,
            function ($query, $value) {
                foreach ($value as $column => $direction){
                    if(in_array($direction, ['asc', 'desc'])){
                        $query->orderBy($column, $direction);
                    }
                }
            }
        );

        return $query;
    }
}