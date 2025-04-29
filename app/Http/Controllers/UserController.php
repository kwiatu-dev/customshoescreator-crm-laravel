<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\User;
use App\Notifications\User\UserCreateNotification;
use App\Notifications\User\UserDeleteNotification;
use App\Notifications\User\UserRestoreNotification;
use App\Notifications\User\UserUpdateNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $fields;

    public function __construct(
        private NotificationService $notificationService
    )
    {
        $this->middleware(['auth', 'verified', 'admin']);

        $this->fields = [
            'first_name', 
            'last_name',
            'email',
            'phone', 
            'street', 
            'street_nr',
            'apartment_nr',
            'postcode', 
            'city',
            'country',
            'costs',
            'commission',
            'distribution',
        ];
    }

    public function index(Request $request){
        $users = User::query()
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        $admins = $admins = User::query()->where('is_admin', true)->get();

        return inertia(
            'User/Index',
            [
                'users' => $users,
                'admins' => $admins,
                'filters' => $request->session()->pull('filters'),
                'sort' => $request->session()->pull('sort'),
            ]
        );
    }

    public function show(User $user) {
        $user->load([
            'projects' => function ($query) {
                $query->with([
                    'images',
                    'user' => function ($query) {
                        $query->withTrashed();
                    },
                    'client' => function ($query) {
                        $query->withTrashed();
                    },
                    'status',
                    'type',
                ]);
            },
        ]);

        $user->append([
            'editable',
            'deletable',
            'restorable',
        ]);
        
        $admins = User::query()->where('is_admin', true)->get();

        return inertia(
            'User/Show',
            [
                'user' => $user,
                'admins' => $admins
            ]
        );
    }

    public function create() {
        $admins = User::query()->where('is_admin', true)->get();

        return inertia(
            'User/Create',
            [
                'users' => $admins,
            ]
        );
    }

    public function store(Request $request){
        $user = User::create([
            ...RequestProcessor::validation($request, $this->fields, new User()),
            'password' => Str::random(20), 
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        $this->notificationService->sendNotification(
            new UserCreateNotification($user, $request->user(), null)
        );

        return redirect()->route('user.index')
            ->with('success', 'Konto użytkownika zostało utworzone!');
    }

    public function edit(User $user) {
        $this->authorize('edit', $user);

        $admins = User::query()->where('is_admin', true)->get();

        return inertia(
            'User/Edit',
            [
                'user' => $user,
                'admins' => $admins,
            ]
        );
    }

    public function update(Request $request, User $user) {
        $this->authorize('update', $user);

        $user->update(
            RequestProcessor::validation($request, $this->fields, $user)
        );

        $this->notificationService->sendNotification(
            new UserUpdateNotification($user, $request->user(), null)
        );

        return redirect()->route('restore.state', ['url' => route('user.index')])->with('success', 'Użytkownik został edytowany!');
    }

    public function destroy(User $user, Request $request) {
        $this->authorize('destroy', $user);

        $user->deleteOrFail();

        $this->notificationService->sendNotification(
            new UserDeleteNotification($user, $request->user(), null)
        );

        return redirect()->back()->with('success', 'Użytkownik został usunięty!');
    }

    public function restore(User $user, Request $request) {
        $this->authorize('restore', $user);

        $user->restore();

        $this->notificationService->sendNotification(
            new UserRestoreNotification($user, $request->user(), null)
        );

        return redirect()->back()->with('success', 'Użytkownik został przywrócony!');
    }
}
