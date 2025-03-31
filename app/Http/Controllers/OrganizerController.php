<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Project;
use App\Models\User;
use App\Models\UserEvents;
use App\Models\UserEventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizerController extends Controller
{
    private $fields;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $user_events = [];
        $projects = [];

        if ($category == null || $category == 'events') {
            $request_events = $request->duplicate();

            if(!Auth::user()?->is_admin){
                $request_events->merge(['user_id' => Auth::user()->id]);
            }

            $user_events = UserEvents::query()
                ->filter($request_events)
                ->get();
        }
        
        if ($category == null || $category == 'projects') {
            $request_projects = clone $request;

            if(!Auth::user()?->is_admin){
                $request_projects->merge(['created_by_user' => true]);
            }

            $filtered_data = collect($request_projects->all())
                ->except(['type_id'])
                ->mapWithKeys(function ($value, $key) {
                    return match ($key) {
                        'user_id' => ['created_by_user_id' => $value],
                        'end_start' => ['deadline_start' => $value],
                        'end_end' => ['deadline_end' => $value],
                        default => [$key => $value],
                    };
                })->toArray();

            $request_projects->query->replace($filtered_data);

            $projects = Project::query()
                ->filter($request_projects, false)
                ->get();
        }

        return inertia(
            'Organizer/Index', 
            [
                'projects' => $projects,
                'userEvents' => $user_events,
                'filters' => $request->session()->pull('filters'),
            ]);
    }
}
