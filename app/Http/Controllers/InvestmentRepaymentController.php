<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Investment;
use App\Models\InvestmentRepayment;
use App\Models\User;
use App\Notifications\Investment\InvestmentStatusNotification;
use App\Notifications\InvestmentRepayment\InvestmentRepaymentCreateNotification;
use App\Notifications\InvestmentRepayment\InvestmentRepaymentDeleteNotification;
use App\Notifications\InvestmentRepayment\InvestmentRepaymentRestoreNotification;
use App\Notifications\InvestmentRepayment\InvestmentRepaymentUpdateNotification;
use App\Services\NotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InvestmentRepaymentController extends Controller
{
    private $fields;

    public function __construct(
        private NotificationService $notificationService)
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

        $footer = $query
            ->filter($request)
            ->footer();

        $investment->load([
            'status',
            'investor' => function ($query) {
                $query->withTrashed();
            },
        ]);

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

        $this->notificationService->sendNotification(
            new InvestmentRepaymentCreateNotification($repayment, $request->user(), null)
        );

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

        $this->notificationService->sendNotification(
            new InvestmentRepaymentUpdateNotification($repayment, $request->user(), null),
        );

        return redirect()
            ->route('restore.state', ['url' => route('repayments.index', ['investment' => $investment->id])])
            ->with('success', 'Zwrot z inwestycji został edytowany!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investment $investment, InvestmentRepayment $repayment, Request $request)
    {
        if (!$repayment->deletable) {
            return redirect()->back()->with('failed', 'Nie można usunać tego zwrotu inwestycji!');
        }

        $investment_status_before = $investment->status_id;
        $investment->addRepaymentValue(-$repayment->repayment);
        $investment_status_after = $investment->status_id;

        if ($investment_status_before != $investment_status_after) {
            $this->notificationService->sendNotification(
                new InvestmentStatusNotification($investment, $request->user(), null),
            );
        }

        $repayment->deleteOrFail();

        $this->notificationService->sendNotification(
            new InvestmentRepaymentDeleteNotification($repayment, $request->user(), null),
        );


        return redirect()->back()->with('success', 'Zwrot inwestycji został usunięty!');
    }

    public function restore(Investment $investment, InvestmentRepayment $repayment, Request $request) {
        if (!$repayment->restorable) {
            return redirect()->back()->with('failed', 'Nie można przywrócić tego zwrotu inwestycji!');
        }

        $investment_status_before = $investment->status_id;
        $investment->addRepaymentValue($repayment->repayment);
        $investment_status_after = $investment->status_id;

        if ($investment_status_before != $investment_status_after) {
            $this->notificationService->sendNotification(
                new InvestmentStatusNotification($investment, $request->user(), null),
            );
        }

        $repayment->restore();

        $this->notificationService->sendNotification(
            new InvestmentRepaymentRestoreNotification($repayment, $request->user(), null),
        );

        return redirect()->back()->with('success', 'Zwrot inwestycji został przywrócony!');
    }
}
