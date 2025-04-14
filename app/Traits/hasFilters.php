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
        
        $query->when(
            $filters['search'] ?? false,
            function ($query, $value){
                $query->where(function ($query) use ($value){
                    foreach($this->searchable as $field => $columns){
                        if (is_array($columns)) {
                            $columns = array_map(function ($column) use ($field) {
                                return $field .'.'. $column;
                            }, $columns);

                            $query->orWhereRaw('CONCAT('. implode(', " ", ', $columns) .') like ' . "'%$value%'");
                        }
                        else if (str_contains('.', $columns)) {
                            $query->orWhere($columns, 'like', "%$value%");
                        }
                        else {
                            $query->orWhere($this->table_name .'.'. $columns, 'like', "%$value%");
                        }
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
                        $query->where($this->table_name .'.'. str_replace("_start", '', $field), '>=', $filters[$field]);
                    }
                    else if(strstr($field, "_end")){
                        $query->where($this->table_name .'.'. str_replace("_end", '', $field), '<=', $filters[$field]);
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
                            $query->where($this->table_name .'.'. str_replace("_start", '', $field), '>=', $filters[$field]);
                        }
                        else if(strstr($field, "_end")){
                            $query->where($this->table_name .'.'. str_replace("_end", '', $field), '<=', $filters[$field]);
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
                        $query->where($this->table_name .'.'. str_replace("_start", '', $field), '>=', $filters[$field]);
                    }
                    else if(strstr($field, "_end")){
                        $query->where($this->table_name .'.'. str_replace("_end", '', $field), '<=', $filters[$field]);
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
                            $query->where($this->table_name .'.'. str_replace("_start", '', $field), '>=', $filters[$field]);
                        }
                        else if(strstr($field, "_end")){
                            $query->where($this->table_name .'.'. str_replace("_end", '', $field), '<=', $filters[$field]);
                        }
                    }
                }
            }
        );

        $query->when(
            $this->filterable['dictionary'] ?? false,
            function ($query, $value) use ($filters) {
                foreach($value as $array){
                    foreach ($array as $field => $type){
                        if(!array_key_exists($field, $filters)){
                            continue;
                        }
                        
                        $query->where($this->table_name .'.'. $field, $filters[$field]);
                    }
                }
            }
        );

        $query->when(
            $filters['created_by_user'] ?? false,
            function ($query, $value){
                $query->where($this->table_name .'.'. 'created_by_user_id', Auth::user()->id);
            }
        );

        $query->when(
            $filters['after_deadline'] ?? false,
            function ($query, $value){
                $query->whereNull($this->table_name . '.end')
                      ->where($this->table_name . '.deadline', '<', now());
            }
        );
        
        return $query;
    }
}