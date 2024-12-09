<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ForgotPassword extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
    }

    public function store(Request $request){
        $validator = Validator::make(
            $request->only('email'), 
            ['email' => 'required|email']
        );

        if($validator->fails()){
            $error = $validator->errors()->first('email');
            return redirect()->back()->with(['failed' => $error]);
        }

        $user = User::query()->where('email', $request->only('email'))->first();

        if ($user?->is_admin) {
            $error = "Nie można zresetować hasła tego użytkownika.";
            return redirect()->back()->with(['failed' => $error]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['success' => 'Wysłano link do zresetowania hasła'])
                    : back()->with(['failed' => __($status)]);
    }
}
