<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPassword extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    public function create(){
        return inertia('Auth/RequestResetPassword');
    }

    public function edit(string $token){
        return inertia(
            'Auth/ResetPassword', 
            [
                'token' => $token
            ]
        );
    }

    public function update(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));
                
                if(!$user->isVerified()){
                    $user->email_verified_at = now(); 
                }

                $user->save();
     
                event(new PasswordReset($user));
            }
        );
        
        return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('success', 'Hasło zostało zresetowane!')
                : back()->withErrors(['email' => [__($status)]]);
    }
}
