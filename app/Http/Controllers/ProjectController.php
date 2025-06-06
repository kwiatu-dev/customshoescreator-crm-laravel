<?php
namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Helpers\RequestProcessor;
use App\Models\ProjectImageType;
use App\Models\ProjectStatus;
use App\Notifications\Income\IncomeCreateNotification;
use App\Notifications\Income\IncomeDeleteNotification;
use App\Notifications\Income\IncomeRestoreNotification;
use App\Notifications\Income\IncomeUpdateNotification;
use App\Notifications\Project\ProjectCreateNotification;
use App\Notifications\Project\ProjectDeleteNotification;
use App\Notifications\Project\ProjectRestoreNotification;
use App\Notifications\Project\ProjectStatusNotification;
use App\Notifications\Project\ProjectUpdateNotification;
use App\Services\NotificationService;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProjectController extends Controller
{
    private $fields;

    public function __construct(
        private ProjectService $projectService,
        private NotificationService $notificationService)
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

        $query = Project::query()
            ->with([
                'status',
                'type',
                'client' => function ($query) {
                    $query->withTrashed();
                },
                'user' => function ($query) {
                    $query->withTrashed();
                },
                'income',
                'images',
            ]);

        $projects = $query
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        $footer = $query
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
                'types' => $types,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = RequestProcessor::validation($request, $this->fields, new Project(), [
            'type_id' => 'required|exists:project_types,id',
            'commission' => 'nullable',
            'costs' => 'nullable',
            'distribution' => ['nullable']
        ]);

        $user = User::findOrFail($fields['created_by_user_id'] ?? Auth::id());
        $fields['created_by_user_id'] = $user->id;

        $project = Project::create([
            ...$fields,
            'commission' => $user->commission,
            'costs' => $user->costs,
            'distribution' => $user->distribution,
            'status_id' => Project::STATUS_AWAITING,
            'remarks' => $fields['remarks'] ?? '',
        ]);

        $tmp_files = $request->input('inspiration_images');
        $project->addImages($tmp_files, ProjectImageType::TYPE_INSPIRATION);

        $this->notificationService->sendNotification(
            new ProjectCreateNotification($project, $request->user(), $user),
        );

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

        $project->append([
            'editable',
            'deletable',
            'restorable',
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
                'statuses' => $statuses,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $before_update_status_id = $project->status_id;

        $fields = RequestProcessor::validation($request, $this->fields, new Project(), [
            'type_id' => 'required|exists:project_types,id',
            'start' => 'required|date|date_format:Y-m-d',
            'deadline' => 'required|date|date_format:Y-m-d',
            'status_id' => 'nullable|integer|exists:project_statuses,id'
        ]);

        $user = User::withTrashed()->find($fields['created_by_user_id'] ?? Auth::id());
        $fields['created_by_user_id'] = $user->id;

        if ($project->created_by_user_id != $user->id) {
            array_merge($fields, [
                'commission' => $user->commission,
                'costs' => $user->costs,
                'distribution' => $user->distribution,
            ]);
        }

        if ($project->status_id != $fields['status_id']) {
            array_merge($fields, [
                'end' => $fields['status_id'] == 3 ? now() : null
            ]);
        }

        $project->update([
            ...$fields,
            'remarks' => $fields['remarks'] ?? '',
        ]);

        $this->notificationService->sendNotification(
            new ProjectUpdateNotification($project, $request->user(), $user),
        );

        $after_update_status_id = $project->status_id;
        $images = $request->input('inspiration_images');
        $project->replaceImages($images, ProjectImageType::TYPE_INSPIRATION);

        $income = $project->income;

        if ($income && $income->deleted_at == null && $after_update_status_id == Project::STATUS_AWAITING) {
            $project->deleteRelatedIncome();

            $this->notificationService->sendNotification(
                new IncomeDeleteNotification($income, $request->user(), null),
            );
        }
        else if ($income && $income->deleted_at != null && $after_update_status_id != Project::STATUS_AWAITING) {
            $project->restoreRelatedIncome();

            $this->notificationService->sendNotification(
                new IncomeRestoreNotification($income, $request->user(), null),
            );
        }

        $project->editRelatedIncome(true);

        $this->notificationService->sendNotification(
            new IncomeUpdateNotification($income, $request->user(), null),
        );

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

            $project->end = now();
        }

        $project->status_id = $status_id;
        $project->save();

        $this->notificationService->sendNotification(
            new ProjectStatusNotification($project, $request->user(), $project->user),
        );

        $income = $project->income;

        if (!$income) {
            $income = $project->createRelatedIncome();

            $this->notificationService->sendNotification(
                new IncomeCreateNotification($income, $request->user(), null),
            );
        }
        else if ($income && $income->deleted_at != null) {
            $project->restoreRelatedIncome(true);

            $this->notificationService->sendNotification(
                new IncomeRestoreNotification($income, $request->user(), null),
            );
        }

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
    public function destroy(Project $project, Request $request)
    {
        $this->authorize('destroy', $project);

        $project->deleteOrFail();

        $this->notificationService->sendNotification(
            new ProjectDeleteNotification($project, $request->user(), $project->user),
        );

        $income = $project->income;

        if ($income) {
            $project->deleteRelatedIncome();

            $this->notificationService->sendNotification(
                new IncomeDeleteNotification($income, $request->user(), null),
            );
        }

        return redirect()->back()->with('success', 'Projekt został usunięty!');
    }

    public function restore(Project $project, Request $request){
        $this->authorize('restore', $project);

        $project->restore();

        $income = $project->income;

        
        $this->notificationService->sendNotification(
            new ProjectRestoreNotification($project, $request->user(), $project->user),
        );

        if ($income) {
            $project->restoreRelatedIncome();

            $this->notificationService->sendNotification(
                new IncomeRestoreNotification($income, $request->user(), null),
            );
        }

        return redirect()->back()->with('success', 'Projekt został przywrócony!');
    }
}
