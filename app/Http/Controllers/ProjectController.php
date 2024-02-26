<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use App\Helpers\RequestProcessor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    private $fields;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $this->fields = [
            'title',
            'remarks',
            'price',
            'start',
            'deadline',
            'commission',
            'costs',
            'distribution',
            'visualization', 
            'created_by_user_id',
            'client_id',
            'status_id',
            'type_id'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(!Auth::user()?->is_admin){
            $request->merge(['created_by_user' => true]);
        }

        $projects = Project::with(['status', 'type', 'client', 'user'])
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        $footer = Project::query()
            ->filter($request)
            ->footer();

        return inertia(
            'Project/Index',
            [
                'projects' => $projects,
                'filters' => $request->session()->pull('filters'),
                'sort' => $request->session()->pull('sort'),
                'footer' => $footer,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::query()->get();
        $clients = Client::query()->latest()->get();
        $types = DB::table('project_types')->get();

        return inertia(
            'Project/Create',
            [
                'users' => $users,
                'clients' => $clients,
                'types' => $types
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = RequestProcessor::validation($request, $this->fields, null, [
            'type_id' => 'required|exists:project_types,id',
            'commission' => 'nullable',
            'costs' => 'nullable',
            'distribution' => 'nullable'
        ]);

        $user = User::find($fields['created_by_user_id'] ?? Auth::id());
        $fields['created_by_user_id'] = $user->id;

        $project = Project::create([
            ...$fields,
            'commission' => $user->commission,
            'costs' => $user->costs,
            'distribution' => $user->distribution,
            'status_id' => 1,
        ]);

        $tmp_files = $request->input('inspiration_images');

        foreach($tmp_files as $file){
            $disk = Storage::disk('private');

            $disk->move(
                $file['path'] .'/'. $file['name'], 
                'projects/'. $project->id .'/'. $file['name']
            );

            $disk->deleteDirectory($file['path']);

            ProjectImage::create([
                'type_id' => 1,
                'project_id' => $project->id,
                'file' => $file['name']
            ]);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Projekt został dodany!');
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
