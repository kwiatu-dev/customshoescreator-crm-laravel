<?php
namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;

class RequestProcessor{
    private static $fields = [
        'first_name' => 'required|string|min:3|max:50', 
        'last_name' => 'required|string|min:3|max:50',
        'email' => 'required|email|unique:{model},email',  
        'phone' => 'required|regex:/\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}/|unique:{model},phone',
        'street' => 'nullable|string|min:3|max:50',
        'street_nr' => 'nullable|string|min:1|max:10',
        'apartment_nr' => 'nullable|string|min:1|max:10',
        'postcode' => 'nullable|string|min:3|max:10',
        'city' => 'nullable|string|min:3|max:25',
        'country' => 'nullable|string|min:3|max:25',
        'costs' => 'required|integer|min:0|max:100',
        'commission' => 'required|integer|min:0|max:100',
        'distribution' => ['required'],
        'title' => 'required|string|min:3|max:50',
        'shop_name' => 'required|string|min:3|max:50',
        'price' => 'required|decimal:0,2|min:0',
        'amount' => 'required|decimal:0,2|min:0',
        'visualization' => 'required|decimal:0,2|min:0',
        'date' => 'required|date|date_format:Y-m-d',
        'file' => 'nullable|mimes:jpg,png,jpeg,pdf|max:5000',
        'remarks' => 'nullable|string|max:150',
        'start' => 'required|date|date_format:Y-m-d|after_or_equal:now',
        'deadline' => 'required|date|date_format:Y-m-d|after_or_equal:now',
        'created_by_user_id' => 'nullable|exists:users,id',
        'client_id' => 'required|exists:clients,id',
        'username' => 'nullable|string|min:3|max:30',
        'conversion_source_id' => 'required|exists:conversion_sources,id',
        'social_link' => 'nullable|url:http,https|max:255',
        'interest_rate' => 'required|integer|min:0'
    ];

    public static function getSortFields(Request $request, array $sortable): array{
        $sort = [];

        foreach($request->all() as $key => $value){
            if(in_array($key, $sortable)){
                $sort[$key] = $value;
            }
            else if (in_array(str_replace('_', '.', $key), $sortable)) {
                $sort[str_replace('_', '.', $key)] = $value;
            }
            else if (array_key_exists($key, $sortable)) {
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

    public static function validation(Request $request, array $fields, Model $model = null, array $custom_validation = null): array{
        $validate = [];

        foreach($fields as $field){
            if(array_key_exists($field, self::$fields)){
                $rule = self::$fields[$field];
                if(in_array($field, ['email', 'phone'])){
                    $rule .= $model ? ",{$model->id}" : '';

                    if ($model) {
                        $rule = str_replace('{model}', strtolower(class_basename($model)) . 's', $rule);
                    }
                    
                }
                $validate[$field] = $rule;
            }
        }

        $validate = array_merge($validate, $custom_validation ?? []);

        $validate['distribution'][] = function($attribute, $value, $fail){
            self::validateDistribution($attribute, $value, $fail);
        };

        if ($value = $request->input('distribution')) {
            $request->merge(['distribution' => self::processDistributionValue($value)]); 
        }

        return $request->validate($validate);
    }

    private static function processDistributionValue($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (is_array($value)) {
            $value = array_filter($value, function ($item) {
                return $item != 0;
            });
        }

        return $value;
    }

    private static function validateDistribution($attribute, $value, $fail){
        if (!is_array($value)) {
            $fail('Pole ' . $attribute . ' musi zawierać poprawną strukturę JSON.');
            return;
        }

        $sum = array_sum($value);

        if ($sum != 100) {
            $fail('Suma wartości w polach musi wynosić równo 100%, aktualna wartość wynosi: ' . $sum . '%');
        }

        foreach ($value as $userId => $percentage) {
            $user = User::where('id', $userId)->withTrashed()->first();

            if (!$user) {
                $fail('Użytkownik o ID ' . $userId . ' nie istnieje lub został usunięty.');
                return;
            }

            if ($user->trashed()) {
                $fail('Użytkownik ' . $user->first_name . ' ' . $user->last_name . ' jest usunięty.');
            }
        }
    }
}
