<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()?->is_admin){
            return $next($request);
        }

        if(Auth::user()){
            return redirect()->back()->with(['failed' => 'Nie posiadasz uprawnień administratora']);
        }
        else{
            return redirect()->route('login');
        }
        
    }
}
