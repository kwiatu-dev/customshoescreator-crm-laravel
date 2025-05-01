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

        $paginator = $query->paginate(6)->onEachSide(0)->withQueryString();

        $paginator->getCollection()->transform(function ($object) {
            if ($object && method_exists($object, 'getEditableAttribute')) {
                $object->append('editable');
            }

            if ($object && method_exists($object, 'getDeletableAttribute')) {
                $object->append('deletable');
            }

            if ($object && method_exists($object, 'getRestorableAttribute')) {
                $object->append('restorable');
            }

            return $object;
        });
        
        return $paginator;
    }
}