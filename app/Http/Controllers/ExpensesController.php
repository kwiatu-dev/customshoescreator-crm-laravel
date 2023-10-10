<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    private $fields;

    public function __construct()
    {
        $this->middleware('admin');

        $this->fields = [
            'title',
            'date',
            'price',
            'shop_name',
            'file_name'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenses = Expenses::query()
            ->filter($request)
            ->sort($request)
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $footer = Expenses::query()
            ->filter($request)
            ->footer();

        return inertia(
            'Expenses/Index',
            [
                'expenses' => $expenses,
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
        return inertia('Expenses/Create');
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
    public function edit(Expenses $expense)
    {
        return inertia('Expenses/Edit');
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
    public function destroy(Expenses $expense)
    {
        $expense->deleteOrFail();

        return redirect()->back()->with('success', 'Wydatek został usunięty!');
    }

    public function restore(Expenses $expense){
        $expense->restore();

        return redirect()->back()->with('success', 'Wydatek został przywrócony!');
    }
}
