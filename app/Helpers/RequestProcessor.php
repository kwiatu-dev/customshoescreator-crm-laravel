<?php
namespace App\Helpers;

use Illuminate\Http\Request;

class RequestProcessor{
    public static function getSortFields(Request $request, array $sortable): array{
        $sort = [];

        foreach($request->all() as $key => $value){
            if(in_array($key, $sortable)){
                $sort[$key] = $value;
            }
        }

        return $sort;
    }

    public static function getFilterFields(Request $request, array $filterable): array{
        $filters = [];

        foreach($filterable as $filter => $type){
            if($type === 'boolean'){
                if($request->boolean($filter)){
                    $filters = array_merge($filters, $request->only($filter));
                }
            }
            else if($type === 'string'){
                if($request->string($filter) != ""){
                    $filters = array_merge($filters, $request->only($filter));
                }
            }
        }

        return $filters;
    }
}