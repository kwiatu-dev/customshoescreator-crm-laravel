<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Investment;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $users = User::query()->get();

        return inertia(
            'Investment/Create',
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
        $fields = RequestProcessor::validation($request, $this->fields, new Investment(), [
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($fields['created_by_user_id'] ?? Auth::id());

        $investment = Investment::create([
            ...$fields,
            'created_by_user_id' => $user->id,
            'status_id' => 1,
            'remarks' => $fields['remarks'] ?? '',
        ]);

        return redirect()->route('investments.index')
            ->with('success', 'Inwestycja została dodana!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Investment $investment)
    {
        $investment->load([
            'status',
            'investor' => function ($query) {
                $query->withTrashed();
            },
            'user' => function ($query) {
                $query->withTrashed();
            }
        ]);

        return inertia('Investment/Show', [
            'investment' => $investment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investment $investment)
    {
        $users = User::query()->get();

        return inertia('Investment/Edit', [
            'investment' => $investment,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Investment $investment)
    {
        if (!$investment->editable) {
            return redirect()->back()->with('failed', 'Nie można edytować tej inwestycji!');
        }

        $fields = RequestProcessor::validation($request, $this->fields, new Investment(), [
            'user_id' => 'required|exists:users,id',
        ]);

        $total = round(($fields['amount'] * $fields['interest_rate'] / 100) + $fields['amount'], 2);

        if ($investment->total_repayment > $total) {
            throw ValidationException::withMessages([
                'amount' => "Kwota inwestycji powiększona o odestki nie może być mniejsza od całkowitej sumy spłat tej inwestycji. Podana kwota inwestycji wynosi: $total zł. Aktualny zwrot z inwestycji wynosi: $investment->total_repayment zł.", 
            ]);
        }

        if ($investment->total_repayment == $total) {
            $fields['status_id'] = 2;
        }

        $investment->update($fields);

        return redirect()->route('restore.state', ['url' => route('investments.index')])->with('success', 'Inwestycja został edytowana!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investment $investment)
    {
        if (!$investment->deletable) {
            return redirect()->back()->with('failed', 'Nie można usunać tej inwestycji!');
        }

        $investment->deleteOrFail();

        return redirect()->back()->with('success', 'Inwestycja została usunięta!');
    }

    public function restore(Investment $investment){
        if (!$investment->restorable) {
            return redirect()->back()->with('failed', 'Nie można przywrócić tej inwestycji!');
        }

        $investment->restore();

        return redirect()->back()->with('success', 'Inwestycja została przywrócona!');
    }
}
