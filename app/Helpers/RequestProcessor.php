<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;

class RequestProcessor{
    private static $fields = [
        'first_name' => 'required|string|min:3|max:50', 
        'last_name' => 'required|string|min:3|max:50',
        'email' => 'required|email|unique:users,email' . "{objectId}",
        'phone' => 'required|regex:/\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}/|unique:users,phone' . "{objectId}",
        'street' => 'nullable|string|min:3|max:50',
        'street_nr' => 'nullable|string|min:1|max:10',
        'apartment_nr' => 'nullable|string|min:1|max:10',
        'postcode' => 'nullable|string|min:3|max:10',
        'city' => 'nullable|string|min:3|max:25',
        'country' => 'nullable|string|min:3|max:25',
        'costs' => 'required|integer|min:0|max:100',
        'commission' => 'required|integer|min:0|max:100',
        'distribution' => 'required|json|max:30',
        'title' => 'required|string|min:3|max:50',
        'shop_name' => 'required|string|min:3|max:50',
        'price' => 'required|decimal:0,2|min:0',
        'visualization' => 'required|decimal:0,2|min:0',
        'date' => 'required|date|date_format:Y-m-d',
        'file' => 'nullable|mimes:jpg,png,jpeg,pdf|max:5000',
        'remarks' => 'nullable|string|max:150',
        'start' => 'required|date|date_format:Y-m-d|after_or_equal:now',
        'deadline' => 'required|date|date_format:Y-m-d|after_or_equal:now',
        'created_by_user_id' => 'nullable|exists:users,id',
        'client_id' => 'required|exists:clients,id'
    ];

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
            if(is_array($type)){
                $filters = array_merge($filters, RequestProcessor::getFilterFields($request, $type));
            }
            else if($type === 'boolean'){
                if($request->boolean($filter)){
                    $filters[$filter] = $request->boolean($filter);
                }
            }
            else if($type === 'string'){
                if($request->string($filter) != ""){
                    $filters[$filter] = $request->string($filter);
                }
            }
            else if($type === 'numeric'){
                if($request->string($filter) != ""){
                    $number = $request->string($filter);

                    if(is_numeric($number->toString())){
                        $filters[$filter] = $number->toFloat();
                    }
                }
            }
            else if($type === 'date'){
                if($request->date($filter)){
                    $filters[$filter] = $request->date($filter)->format('Y-m-d');
                }
            }
        }

        return $filters;
    }

    public static function validation(Request $request, array $fields, Model $user = null, array $custom_validation = null): array{
        $validate = [];

        foreach($fields as $field){
            if(array_key_exists($field, self::$fields)){
                $validate[$field] = str_replace(
                    "{objectId}", 
                    ($user ? ",$user->id" : ''), 
                    self::$fields[$field]);
            }
        }

        return $request->validate(
            array_merge($validate, $custom_validation));
    }

    public static function rememberPreviousUrl(){
        if(url()->previous() !== url()->current())
            session()->put('back_to_url', url()->previous());
    }

    public static function backToPreviousUrlOrRoute(string $route, string $message): RedirectResponse{
        $back_to_url = session()->pull('back_to_url');

        if($back_to_url)
            return redirect($back_to_url)->with('success', $message);
        else
            return redirect()->route($route)->with('success', $message);
    }
}