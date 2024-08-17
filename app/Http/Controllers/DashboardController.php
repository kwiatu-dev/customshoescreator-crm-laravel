<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    
    public function index(){
        return inertia(
            'Dashboard/Index',
        );
    }
}
