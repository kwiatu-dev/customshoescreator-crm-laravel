<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create(){
        if(Auth::user())
            return redirect()->route('dashboard.index');

        return inertia(
            'Auth/Login'
        );
    }

    public function store(Request $request){
        if(!Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]), $request->boolean('remember'))){
            throw ValidationException::withMessages([
                'email' => 'Wprowadzono niepoprawny email lub hasło'
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended();
    }

    public function destroy(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            $user->tokens()
                ->where('name', 'chat-widget')
                ->delete();
        }

        return redirect()->route('dashboard.index');
    }
}
