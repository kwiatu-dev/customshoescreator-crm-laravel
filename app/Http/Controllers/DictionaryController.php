<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectStatus;
use Illuminate\Support\Facades\Auth;

class DictionaryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, string $table){
        if (!class_exists('App\Models\\' . $table)) {
            abort(404, "Model for table '$table' not found");
        }

        $data = null;

        if($table === "User"){
            abort_if(
                !Auth::user()?->is_admin,
                401,
                "Unauthorized"
            );

            $data = call_user_func(
                "App\Models\\" . $table . "::select", 
                'id', 
                \DB::raw("CONCAT(first_name, ' ', last_name) AS name")
            )->get();
        }
        else{
            $data = call_user_func(
                "App\Models\\" . $table . "::select", 
                'id', 
                'name'
            )->get();
        }

        return response()->json($data);
    }
}
