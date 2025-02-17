<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    private $fields;

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);

        $this->fields = [
            'title',
            'amount',
            'date',
            'interest_rate',
            'total_repayment',
            'remarks',
            'user_id',
            'status_id',
            'created_by_user_id'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Investment::query()
            ->with([
                'status',
                'investor' => function ($query) {
                    $query->withTrashed();
                },
                'user' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->leftJoinRelation('status as status')
            ->leftJoinRelation('investor as investor')
            ->addSelect([
                'investments.*',
            ]);

        $investments = $query
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        $footer = Investment::query()
            ->filter($request)
            ->footer();

        return inertia(
            'Investment/Index',
            [
                'investments' => $investments,
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
