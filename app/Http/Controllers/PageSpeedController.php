<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PageSpeedController extends Controller
{
    public function index(){
        return inertia(
            'PageSpeed/Index',
        );
    }

    public function audit(Request $request)
    {
        $key = env("PAGESPEED_API_KEY");
        $endpoint = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed";
        $url = $request->input('url');

        $response = Http::get($endpoint, [
            'url' => $url,
            'key' => $key,
        ]);

        $data = $response->json();

        //dd($data);

        return inertia(
            'PageSpeed/Index',
            [
                'data' => $data,
            ]
        );
    }
}
