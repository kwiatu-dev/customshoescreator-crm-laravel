<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'navigation' => ['history' => $request->session()->get('navigation_history')],
            'flash' => [
                'success' => $request->session()->get('success'),
                'failed' => $request->session()->get('failed'),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'currentUser' => $request->user() ? [
                'id' => $request->user()->id,
                'first_name' => $request->user()->first_name,
                'last_name' => $request->user()->last_name,
                'email' => $request->user()->email,
                'is_admin' => $request->user()->is_admin,
                'unread_notifications_count' => $request->user()->unreadNotifications()->count(),
                'unread_notifications' => $request->user()->unreadNotifications()->limit(10)->get(),
            ] : null,
            'inertia' => $request->session()->get('inertia', []),
            'csrfToken' => csrf_token()
        ]);
    }
}
