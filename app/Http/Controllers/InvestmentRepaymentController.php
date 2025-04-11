<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Investment;
use App\Models\InvestmentRepayment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
    public function index(Investment $investment, Request $request)
    {
        $query = $investment->repayments();
        $repayment_count = $query->count(); 

        $repayments = $query
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        $footer = InvestmentRepayment::query()
            ->where('investment_id', $investment->id)
            ->filter($request)
            ->footer();

        $investment->append([
            'editable',
            'deletable',
            'restorable',
        ]);

        return inertia('InvestmentRepayment/Index', [
            'repayments' => $repayments,
            'investment' => $investment,
            'filters' => $request->session()->pull('filters'),
            'sort' => $request->session()->pull('sort'),
            'footer' => $footer,
            'repaymentCount' => $repayment_count
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Investment $investment)
    {
        return inertia('InvestmentRepayment/Create', [
            'investment' => $investment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Investment $investment, Request $request)
    {
        $fields = RequestProcessor::validation($request, $this->fields, new InvestmentRepayment(), [
            'date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:' . $investment->date,
            ],
        ]);

        $investment->addRepaymentValue($fields['repayment']);
        $user = User::find($fields['created_by_user_id'] ?? Auth::id());

        $repayment = InvestmentRepayment::create([
            ...$fields,
            'investment_id' => $investment->id,
            'created_by_user_id' => $user->id,
        ]);

        return redirect()->route('repayments.index', ['investment' => $investment->id])
            ->with('success', 'Zwrot z inwestycji został dodany!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investment $investment, InvestmentRepayment $repayment)
    {
        return inertia('InvestmentRepayment/Edit', [
            'investment' => $investment,
            'repayment' => $repayment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Investment $investment, InvestmentRepayment $repayment, Request $request)
    {
        if (!$repayment->editable) {
            return redirect()->back()->with('failed', 'Nie można edytować tego zwrotu inwestycji!');
        }

        $fields = RequestProcessor::validation($request, $this->fields, new InvestmentRepayment(), [
            'date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:' . $investment->date,
            ],
        ]);

        $repayment_value = $repayment->repayment - $fields['repayment']; 
        $investment->addRepaymentValue(-$repayment_value);
        $repayment->update($fields);

        return redirect()
            ->route('restore.state', ['url' => route('repayments.index', ['investment' => $investment->id])])
            ->with('success', 'Zwrot z inwestycji został edytowany!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investment $investment, InvestmentRepayment $repayment)
    {
        if (!$repayment->deletable) {
            return redirect()->back()->with('failed', 'Nie można usunać tego zwrotu inwestycji!');
        }

        $investment->addRepaymentValue(-$repayment->repayment);
        $repayment->deleteOrFail();


        return redirect()->back()->with('success', 'Zwrot inwestycji został usunięty!');
    }

    public function restore(Investment $investment, InvestmentRepayment $repayment) {
        if (!$repayment->restorable) {
            return redirect()->back()->with('failed', 'Nie można przywrócić tego zwrotu inwestycji!');
        }

        $investment->addRepaymentValue($repayment->repayment);
        $repayment->restore();

        return redirect()->back()->with('success', 'Zwrot inwestycji został przywrócony!');
    }
}
