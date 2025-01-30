<?php

namespace App\Traits;

trait HasPagination
{
    public function scopePagination($query)
    {
        $filters = session()->get('filters');
        
        if($filters['pagination'] ?? false){
            $paginate = $filters['pagination']->toInteger();
            return $query->paginate($paginate)->withQueryString();
        }
        
        return $query->paginate(6)->onEachSide(0)->withQueryString();
    }
}