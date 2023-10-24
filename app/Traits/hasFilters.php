<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Helpers\RequestProcessor;
use Illuminate\Support\Facades\Auth;

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
            $this->filterable['dates'] ?? false,
            function ($query, $value) use ($filters) {
                foreach($value as $array){
                    foreach ($array as $field => $type){
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

        $query->when(
            $this->filterable['numbers'] ?? false,
            function ($query, $value) use ($filters) {
                foreach($value as $array){
                    foreach ($array as $field => $type){
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
            }
        );

        $query->when(
            $filters['status_id'] ?? false,
            function ($query, $value){
                $query->where('status_id', $value);
            }
        );

        $query->when(
            $filters['type_id'] ?? false,
            function ($query, $value){
                $query->where('type_id', $value);
            }
        );

        $query->when(
            $filters['created_by_user'] ?? false,
            function ($query, $value){
                $query->where('created_by_user_id', Auth::user()->id);
            }
        );

        $query->when(
            $filters['created_by_user_id'] ?? false,
            function ($query, $value){
                $query->where('created_by_user_id', $value);
            }
        );
        
        return $query;
    }
}