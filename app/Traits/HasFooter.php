<?php

namespace App\Traits;

trait HasFooter
{
    public function scopeFooter($query)
    {
        $footer = [];
        
        if (method_exists($this, 'setFooterDynamicFields')) {
            $this->setFooterDynamicFields();
        }

        foreach($this->footer as $field => $action){
            if($action === 'sum'){
                $footer[$field] = round($query->get()->sum(function ($row) use ($field) {
                    return $row[$field];
                }), 2);
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