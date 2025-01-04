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
use App\Models\ProjectStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

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

        $projects = Project::query()
        ->with([
            'status',
            'type',
            'client' => function ($query) {
                $query->withTrashed();
            },
            'user' => function ($query) {
                $query->withTrashed();
            },
            'images'
        ])
        ->filter($request)
        ->sort($request)
        ->latest()
        ->pagination();

        $footer = Project::query()
            ->filter($request)
            ->footer();

        $projects->each(function ($project) {
            $project->images->each(function ($image) {
                $image->url = route('private.files', ['catalog' => 'projects', 'file' => $image->file]);
            });
        });
        
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
            'remarks' => $fields['remarks'] ?? '',
        ]);

        $tmp_files = $request->input('inspiration_images');
        $project->addImages($tmp_files, 1);

        return redirect()->route('projects.index')
            ->with('success', 'Projekt został dodany!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Request $request)
    {
        $this->authorize('show', $project);

        $users = User::query()->get();

        $project->load([
            'images',
            'user' => function ($query) {
                $query->withTrashed();
            },
            'client' => function($query) {
                $query->withTrashed();
            },
            'status',
            'type'
        ]);

        $project->images->each(function ($image) {
            $image->url = route('private.files', ['catalog' => 'projects', 'file' => $image->file]);
        });

        return inertia(
            'Project/Show',
            [
                'project' => $project,
                'users' => $users,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Request $request)
    {
        $this->authorize('edit', $project);
        $users = User::query()->get();
        $clients = Client::query()->latest()->get();
        $types = DB::table('project_types')->get();
        $statuses = ProjectStatus::query()->get();

        $project = $project->load([
            'images',
            'user' => function ($query) {
                $query->withTrashed();
            },
            'client' => function ($query) {
                $query->withTrashed();
            },
            'status',
            'type'
        ]);

        if (!$users->contains('id', $project->user->id)) {
            $users->push($project->user);
        }

        if (!$clients->contains('id', $project->client->id)) {
            $clients->push($project->client);
        }

        $project->images->each(function ($image) {
            $image->url = route('private.files', ['catalog' => 'projects', 'file' => $image->file]);
        });

        return inertia(
            'Project/Edit',
            [
                'project' => $project,
                'users' => $users,
                'clients' => $clients,
                'types' => $types,
                'statuses' => $statuses
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $fields = RequestProcessor::validation($request, $this->fields, null, [
            'type_id' => 'required|exists:project_types,id',
            'start' => 'required|date|date_format:Y-m-d',
            'deadline' => 'required|date|date_format:Y-m-d',
            'status_id' => 'nullable|integer|exists:project_statuses,id'
        ]);
        dd($fields);

        $user = User::withTrashed()->find($fields['created_by_user_id'] ?? Auth::id());
        $fields['created_by_user_id'] = $user->id;

        if ($project->created_by_user_id != $user->id) {
            array_merge($fields, [
                'commission' => $user->commission,
                'costs' => $user->costs,
                'distribution' => $user->distribution,
            ]);
        }

        $project->update([
            ...$fields,
            'remarks' => $fields['remarks'] ?? '',
        ]);

        $images = $request->input('inspiration_images');
        $project->replaceImages($images, 0);

        return redirect()->route('restore.state', ['url' => route('projects.index')])->with('success', 'Projekt został edytowany!');
    }

    public function status(Request $request, Project $project)
    {
        $this->authorize('status', $project);

        $validator = Validator::make($request->all(), [
            'status_id' => 'required|integer|exists:project_statuses,id'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())
            ->with('failed', 'Nie udało się zaktualizować statusu projektu!');
        }

        $status_id = $request->integer('status_id');

        if ($status_id == 3) {
            $errors = [];
            $hasVisualizationPrice = $project->visualization > 0;
            $hasVisualizationImages = $project->images()->where('type_id', 1)->exists();
            $hasAtLeastFiveProcessImages = $project->images()->where('type_id', 2)->count() >= 5;
            $hasAtLeastFiveFinalImages = $project->images()->where('type_id', 3)->count() >= 5;

            if ($hasVisualizationPrice == true && $hasVisualizationImages == false) {
                $errors = array_merge($errors, ['visualization' => 'Wizualizacja musi zostać przesłana przed zakończeniem projektu.']);
            } 

            if ($hasAtLeastFiveProcessImages == false) {
                $errors = array_merge($errors, ['process' => 'Co najmniej 5 zdjęć z procesu realizacji musi zostać przesłane przed zakończeniem projektu.']);
            }

            if ($hasAtLeastFiveFinalImages == false) {
                $errors = array_merge($errors, ['final' => 'Co najmniej 5 zdjęć końcowych musi zostać przesłane przed zakończeniem projektu.']);
            }

            if (count($errors) > 0) {
                return redirect()->back()->withErrors($errors);
            }
        }

        $project->status_id = $status_id;
        $project->save();

        return redirect()->back()
            ->with('success', 'Status projektu został zaktualizowany!');
    }

    public function upload(Request $request, Project $project){
        $this->authorize('upload', $project);

        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer|exists:project_image_types,id'
        ]);

        if(!$validator->fails()){
            $type_id = $request->integer('type_id');
            $images = $request->input('images');
            $project->replaceImages($images, $type_id);

            return redirect()->back();
        }

        return redirect()->back()->withErrors($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('destroy', $project);

        $project->deleteOrFail();

        return redirect()->back()->with('success', 'Projekt został usunięty!');
    }

    public function restore(Project $project){
        $this->authorize('restore', $project);

        $project->restore();

        return redirect()->back()->with('success', 'Projekt został przywrócony!');
    }
}
