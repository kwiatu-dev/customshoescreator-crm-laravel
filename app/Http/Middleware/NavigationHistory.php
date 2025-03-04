<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class NavigationHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUrl = $request->fullUrl();
        $history = Session::get('navigation_history', []);

        if (end($history) !== $currentUrl &&
            !$this->isApiRequest($currentUrl) &&
            $request->isMethod('GET')) {
            $history[] = $currentUrl;
        }

        if (count($history) > 50) {
            array_shift($history);
        }

        Session::put('navigation_history', $history);

        return $next($request);
    }

    private function isApiRequest(string $url)
    {
        $apiPatterns = [
            '/api',
            '/dictionary',
            '/remember-state',
            '/restore-state'
        ];

        foreach ($apiPatterns as $pattern) {
            if (strpos($url, $pattern) !== false) {
                return true;  
            }
        }

        return false;
    }
}
