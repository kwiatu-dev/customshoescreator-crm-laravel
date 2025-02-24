<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;

class InvestmentRepaymentController extends Controller
{
    private $fields;

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);

        $this->fields = [
            'repayment',
            'date',
            'remarks',
            'investment_id',
            'created_by_user_id'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $investmentId = $request->query('investmentId');
        $investment = Investment::withTrashed()->findOrFail($investmentId);
        $investment->load([
            'status',
            'user' => function ($query) {
                $query->withTrashed();
            },
            'investor' => function ($query) {
                $query->withTrashed();
            }
        ]);

        $query = $investment->repayments();

        $repayments = $query
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        $footer = Investment::query()
            ->filter($request)
            ->footer();

        return inertia('InvestmentRepayment/Index', [
            'repayments' => $repayments,
            'investment' => $investment,
            'filters' => $request->session()->pull('filters'),
            'sort' => $request->session()->pull('sort'),
            'footer' => $footer,
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
