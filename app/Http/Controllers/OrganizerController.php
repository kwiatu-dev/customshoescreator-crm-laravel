<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(!Auth::user()?->is_admin){
            $request->merge(['user_id' => Auth::user()->id]);
        }

        $category = $request->query('category');
        $user_events = [];
        $projects = [];

        if ($category == null || $category == 'events') {
            $user_events = UserEvents::query()
                ->with(['type'])
                ->filter($request)
                ->get();
        }
        
        if ($category == null || $category == 'projects') {
            $project_controller = app(\App\Http\Controllers\ProjectController::class);
            $projects = $project_controller->getUserProjects(Auth::user())->original;
        }

        return inertia(
            'Organizer/Index', 
            [
                'projects' => $projects,
                'userEvents' => $user_events,
                'filters' => $request->session()->pull('filters'),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
