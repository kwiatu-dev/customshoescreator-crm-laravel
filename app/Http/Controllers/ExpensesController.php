<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use App\Helpers\RequestProcessor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'file'
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
            ->pagination();

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
        $fields = RequestProcessor::validation($request, $this->fields);
        $file = $request->hasFile('file') ? $request->file('file') : null;

        if($file && $file->isValid()){
            $path = $file->store('expenses', 'private');
            $fields['file'] = $path;
        }
        else{
            unset($fields['file']);
        }

        Auth::user()->expenses()->save(new Expenses($fields));

        return redirect()->route('expenses.index')
            ->with('success', 'Wydatek został dodany!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expenses $expense)
    {
        RequestProcessor::rememberPreviousUrl();

        return inertia(
            'Expenses/Edit',
            [
                'expense' => $expense,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expenses $expense)
    {
        $fields = RequestProcessor::validation($request, $this->fields);   
        $file = $request->hasFile('file') ? $request->file('file') : null;

        if($file && $file->isValid()){
            $path = $file->store('expenses', 'private');
            $fields['file'] = $path;
        }
        else{
            unset($fields['file']);
        }

        $expense->update($fields);

        return RequestProcessor::backToPreviousUrlOrRoute(
            'expenses.index', 
            'Wydatek został edytowany!'
        );
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

    public function remove(Expenses $expense){
        $catalog = $expense->file['catalog'];
        $file = $expense->file['file'];
        $disk = Storage::disk('private');

        if($disk->exists("$catalog/$file")){
            $disk->delete("$catalog/$file");
            $expense->file = null;
            $expense->save();

            return redirect()->back()->with('success', 'Faktura została usunięta!');
        }
        else{
            return redirect()->back()->with('failed', 'Wystapił błąd podczas usuwania faktury!');
        }
    }
}
