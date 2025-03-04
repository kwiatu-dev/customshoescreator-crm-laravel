<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class NavigationHistoryController extends Controller
{
    public function update(Request $request) {
        $retain_count = $request->input('retain_count', 0);
        $history = Session::get('navigation_history', []);
        $url = $history[$retain_count];

        if ($retain_count > 0) {
            $history = array_slice($history, 0, $retain_count);
        }
        else if ($retain_count == 0) {
            $history = [];
        } 

        Session::put('navigation_history', $history);

        // if (!empty($url)) {
        //     return redirect()->away($url);
        // }

        return redirect()->back();
    }
}
