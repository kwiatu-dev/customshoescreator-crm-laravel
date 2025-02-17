<?php

namespace App\Traits;

trait HasFooter
{
    //nie sprawdzone dla relacyjnych kolumn
    public function scopeFooter($query)
    {
        $footer = [];
        
        if (method_exists($this, 'setFooterDynamicFields')) {
            $this->setFooterDynamicFields();
        }

        foreach($this->footer as $field => $action){
            if($action === 'sum'){
                $footer[$field] = round($query->sum($this->table_name .'.'. $field), 2);
            }
            elseif (is_callable($action)) {
                $footer[$field] = round($query->get()->sum(function ($row) use ($action) {
                    return $action($row);
                }), 2);
            }
        }

        return $footer;
    }
}