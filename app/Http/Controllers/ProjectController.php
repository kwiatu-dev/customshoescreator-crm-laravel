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

        $projects = Project::select('*', DB::raw('(CASE WHEN status_id != 3 THEN true ELSE false END) as editable')) //todo: ustawić odpowiedni warunek na editable
            ->with(['status', 'type', 'client', 'user', 'images'])
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
    public function show(Project $project)
    {
        $project->load(['images', 'user', 'client', 'status', 'type']);
        $project->editable = $project->status_id != 3 ? 1 : 0;

        $project->images->each(function ($image) {
            $image->url = route('private.files', ['catalog' => 'projects', 'file' => $image->file]);
        });

        return inertia(
            'Project/Show',
            [
                'project' => $project,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        RequestProcessor::rememberPreviousUrl();

        return inertia(
            'Project/Edit',
            [
                'project' => $project,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function status(Request $request, Project $project)
    {
        //todo: sprawdź możliwość edycji projektu
        if(true){
            $validator = Validator::make($request->all(), [
                'status_id' => 'required|integer|exists:project_statuses,id'
            ]);

            if(!$validator->fails()){
                $status_id = $request->integer('status_id');
                $project->status_id = $status_id;
                $project->save();

                return redirect()->back()
                    ->with('success', 'Status projektu został zaktualizowany!');
            }

            return redirect()->back()->withErrors($validator->errors())
                ->with('failed', 'Nie udało się zaktualizować statusu projektu!');
        }
    }

    public function upload(Request $request, Project $project){
        //todo: sprawdź możliwość edycji projektu
        if(true){
            $validator = Validator::make($request->all(), [
                'type_id' => 'required|integer|exists:project_image_types,id'
            ]);

            if(!$validator->fails()){
                $type_id = $request->integer('type_id');
                $images = $request->input('images');
                $project->addImages($images, $type_id);

                return redirect()->back();
            }

            return redirect()->back()->withErrors($validator->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->deleteOrFail();

        return redirect()->back()->with('success', 'Projekt został usunięty!');
    }

    public function restore(Project $project){
        $project->restore();

        return redirect()->back()->with('success', 'Projekt został przywrócony!');
    }
}
