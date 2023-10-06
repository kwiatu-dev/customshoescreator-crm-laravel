<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request){
        $users = User::query()
            ->filter($request)
            ->sort($request)
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return inertia(
            'User/Index',
            [
                'users' => $users,
                'filters' => $request->session()->pull('filters'),
                'sort' => $request->session()->pull('sort'),
            ]
        );
    }

    public function create() {
        return inertia('User/Create');
    }

    public function store(Request $request) {
        User::create([
            ...$this->validation($request),
            'password' => Str::random(20), 
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        return redirect()->route('home')
            ->with('success', 'Konto użytkownika zostało utworzone!');
    }

    private function validation(Request $request, User $user = null): array{
        return $request->validate([
            'first_name' => 'required|string|min:3|max:50', 
            'last_name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email' . ($user ? ",$user->id" : ''),
            'phone' => 'required|regex:/\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}/|unique:users,phone' . ($user ? ",$user->id" : ''),
            'street' => 'nullable|string|min:3|max:50',
            'street_nr' => 'nullable|string|min:1|max:10',
            'apartment_nr' => 'nullable|string|min:1|max:10',
            'postcode' => 'nullable|string|min:3|max:10',
            'city' => 'nullable|string|min:3|max:25',
            'country' => 'nullable|string|min:3|max:25',
            'costs' => 'required|integer|min:0|max:100',
            'commission' => 'required|integer|min:0|max:100',
            'distribution' => 'required|json|max:30',
        ]);
    }
}
