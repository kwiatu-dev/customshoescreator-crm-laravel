<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\User\UserResetPasswordNotification;
use App\Services\NotificationService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Notification;

class ResetPassword extends Controller
{
    public function __construct(
        private NotificationService $notificationService)
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
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::query()->where('email', $request->only('email'))->first();
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));
                
                if(!$user->isVerified()){
                    $user->email_verified_at = now(); 
                }

                $user->save();
     
                event(new PasswordReset($user));

                $this->notificationService->sendNotification(
                    new UserResetPasswordNotification($user, $request->user(), null)
                );
            }
        );

        return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('success', 'Hasło zostało zresetowane!')
                : back()->withErrors(['email' => [__($status)]]);
    }
}
