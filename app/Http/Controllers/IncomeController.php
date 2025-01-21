<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Income;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $query = Income::query()
        ->with([
            'status',
            'user' => function ($query) {
                $query->withTrashed();
            },
            'project' => function ($query) {
                $query->withTrashed();
            }
        ])
        ->leftJoinRelation('status as status')
        ->addSelect([
            'incomes.*',
        ]);

        $incomes = $query
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        $footer = $query
            ->filter($request)
            ->footer();

        $users = User::query()->get();

        return inertia(
            'Income/Index',
            [
                'incomes' => $incomes,
                'filters' => $request->session()->pull('filters'),
                'sort' => $request->session()->pull('sort'),
                'footer' => $footer,
                'users' => $users
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::query()->withTrashed()->get();

        return inertia(
            'Income/Create',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = RequestProcessor::validation($request, $this->fields, null, [
            'distribution' => ['nullable'],
            'date' => 'nullable|date|date_format:Y-m-d',
        ]);

        if (array_key_exists('date', $fields) && $fields['date']) {
            $fields['status_id'] = 2;
        }
        else {
            $fields['status_id'] = 1;
        }

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
    public function edit(Income $income)
    {
        $users = User::query()->withTrashed()->get();

        return inertia(
            'Income/Edit',
            [
                'income' => $income,
                'users' => $users
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        $fields = RequestProcessor::validation($request, $this->fields, null, [
            'distribution' => ['nullable'],
            'date' => 'nullable|date|date_format:Y-m-d',
        ]);

        if (array_key_exists('date', $fields) && $fields['date']) {
            $fields['status_id'] = 2;
        }
        else {
            $fields['status_id'] = 1;
        }

        $income->update($fields);

        return redirect()->route('restore.state', ['url' => route('incomes.index')])->with('success', 'Przychód został edytowany!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        if ($income->deletable) {
            $income->deleteOrFail();
    
            return redirect()->back()->with('success', 'Przychód został usunięty!');
        }

        return redirect()->back()->with('failed', 'Przychód nie może zostać usunięty');
    }

    public function restore(Income $income)
    {
        if ($income->restorable) {
            $income->restore();

            return redirect()->back()->with('success', 'Przychód został przywrócony!');
        }

        return redirect()->back()->with('failed', 'Przychód nie może zostać przywrócony');
    }

    public function settle(Income $income, Request $request) {
        $parsedUrl = parse_url(url()->previous());
        parse_str($parsedUrl['query'] ?? '', $queryParams);
        unset($queryParams['settle']);
        $newQueryString = http_build_query($queryParams);
        $redirectUrl = $parsedUrl['path'] . ($newQueryString ? '?' . $newQueryString : '');

        if ($income->status_id == 1) {
            $income->date = Carbon::now();
            $income->status_id = 2;
            $income->save();

            return redirect()->to($redirectUrl)->with('success', 'Przychód został rozliczony');
        }

        return redirect()->to($redirectUrl)->with('failed', 'Przychód nie może zostać rozliczony');
    }
}
