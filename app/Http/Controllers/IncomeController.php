<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Income;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    private $fields;

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);

        $this->fields = [
            'title',
            'remarks',
            'date',
            'price',
            'created_by_user_id',
            'project_id',
            'status_id',
            'costs',
            'distribution'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $incomes = Income::query()
        ->with([
            'status',
            'user' => function ($query) {
                $query->withTrashed();
            },
            'project' => function ($query) {
                $query->withTrashed();
            }
        ])
        ->leftJoinRelation('status')
        ->leftJoinRelation('user', function ($join) {
            $join->withTrashed();
        })
        ->leftJoinRelation('project', function ($join) {
            $join->withTrashed();
        })
        ->addSelect([
            'incomes.*',
        ])
        ->filter($request)
        ->sort($request)
        ->latest()
        ->pagination();

        $footer = Income::query()
            ->filter($request)
            ->footer();

        return inertia(
            'Income/Index',
            [
                'incomes' => $incomes,
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
        $admins = User::query()->where('is_admin', true)->get();

        return inertia(
            'Income/Create',
            [
                'admins' => $admins,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = RequestProcessor::validation($request, $this->fields, null, [
            'distribution' => ['nullable']
        ]);

        $fields['status_id'] = 2;

        Auth::user()->incomes()->save(new Income($fields));

        return redirect()->route('incomes.index')
            ->with('success', 'Przychód został dodany!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return inertia('Income/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return inertia('Income/Edit');
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
