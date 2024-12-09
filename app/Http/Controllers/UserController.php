<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $fields;

    public function __construct()
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
        return inertia('User/Show');
    }

    public function create() {
        return inertia('User/Create');
    }

    public function store(Request $request){
        User::create([
            ...RequestProcessor::validation($request, $this->fields),
            'password' => Str::random(20), 
        ]);

        Password::sendResetLink(
            $request->only('email')
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

        return redirect()->route('user.index')->with('success', 'Użytkownik został edytowany!');
    }

    public function destroy(User $user) {
        $this->authorize('destroy', $user);

        $user->deleteOrFail();

        return redirect()->back()->with('success', 'Użytkownik został usunięty!');
    }

    public function restore(User $user){
        $this->authorize('restore', $user);

        $user->restore();

        return redirect()->back()->with('success', 'Użytkownik został przywrócony!');
    }
}
