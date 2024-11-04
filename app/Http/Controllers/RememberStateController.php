<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RememberStateController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'params' => 'nullable|array',
        ]);

        if (!empty($validated)) {
            session()->put('remember_state', $validated['params']);
        }

        return redirect($validated['url']);
    }
}
