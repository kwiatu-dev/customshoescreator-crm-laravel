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
            $filters['search'] ?? false,
            function ($query, $value){
                $query->where(function ($query) use ($value){
                    foreach($this->searchable as $field){
                        $query->orWhere($field, 'like', "%$value%");
                    }
                });
            }
        );

        $query->when(
            $filters['deleted'] ?? false,
            fn ($query, $value) => $query->withTrashed()
        );

        $query->when(
            $this->filterable['date'] ?? false,
            function ($query, $value) use ($filters) {
                foreach ($value as $field => $type){
                    if(!array_key_exists($field, $filters)){
                        continue;
                    }
                    
                    if(strstr($field, "_start")){
                        $query->where(str_replace("_start", '', $field), '>=', $filters[$field]);
                    }
                    else if(strstr($field, "_end")){
                        $query->where(str_replace("_end", '', $field), '<=', $filters[$field]);
                    }
                }
            }
        );

        $query->when(
            $this->filterable['number'] ?? false,
            function ($query, $value) use ($filters) {
                foreach ($value as $field => $type){
                    if(!array_key_exists($field, $filters)){
                        continue;
                    }
                    
                    if(strstr($field, "_start")){
                        $query->where(str_replace("_start", '', $field), '>=', $filters[$field]);
                    }
                    else if(strstr($field, "_end")){
                        $query->where(str_replace("_end", '', $field), '<=', $filters[$field]);
                    }
                }
            }
        );
        
        return $query;
    }
}