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
                foreach ($value as $column => $data){
                    if (is_array($data)) {
                        //todo: 1. dodać sortowanie po wybranych kolumnach
                        //$query->

                    }
                    else if(in_array($data, ['asc', 'desc'])){
                        $query->orderBy($column, $data);
                    }
                }
            }
        );

        //dd($query->toSql(), $query->getBindings());

        return $query;
    }
}