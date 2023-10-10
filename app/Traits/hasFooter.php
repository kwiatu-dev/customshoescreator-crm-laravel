<?php

namespace App\Traits;

trait HasFooter
{
    public function scopeFooter($query)
    {
        $footer = [];

        foreach($this->footer as $field => $action){
            if($action === 'sum'){
                $footer[$field] = $query->sum($field);
            }
        }

        return $footer;
    }
}