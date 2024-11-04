<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestoreStateController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $params = session()->pull('remember_state', []);

        if (!empty(session()->get('success'))) {
            session()->flash('success', session()->get('success'));
        }

        if (!empty(session()->get('failed'))) {
            session()->flash('failed', session()->get('failed'));
        }

        return redirect()->away($validated['url'] . '?' . http_build_query($params));
    }
}
