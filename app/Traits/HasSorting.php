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
                foreach ($value as $field => $data){
                    if (array_key_exists($field, $this->sortable)){
                        $columns = $this->sortable[$field];

                        $columns = array_map(function ($column) use ($field) {
                            return $field .'.'. $column;
                        }, $columns);
                        
                        if ($columns) {
                            if (in_array($data, ['asc', 'desc'])) {
                                $query->orderByRaw('CONCAT_WS(\' \', '. implode(', ', $columns) .') ' . $data);
                            } 
                        }
                    }
                    else if (str_contains($field, '.')) {
                        $query->orderBy($field, $data);
                    }
                    else if(in_array($data, ['asc', 'desc'])){
                        $query->orderBy($this->table_name .'.'. $field, $data);
                    }
                }
            }
        );

        return $query;
    }
}