<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Client;
use App\Models\ConversionSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $fields;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $this->fields = [
            'first_name',
            'last_name',
            'email',
            'phone', 
            'street',
            'street_nr', 
            'apartment_nr', 
            'postcode', 
            'city', 
            'country', 
            'username', 
            'conversion_source_id', 
            'social_link',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::with('conversion_source')
            ->filter($request)
            ->sort($request)
            ->latest()
            ->pagination();

        return inertia(
            'Client/Index',
            [
                'clients' => $clients,
                'filters' => $request->session()->pull('filters'),
                'sort' => $request->session()->pull('sort'),
            ]
        );
    }

    public function show(Client $client) { 
        $client->load([
            'projects' => function ($query) {
                $query->with([
                    'images', 
                    'user' => function ($query) {
                        $query->withTrashed(); 
                    },
                    'client' => function ($query) {
                        $query->withTrashed(); 
                    },
                    'status', 
                    'type', 
                ]);
            },
        ]);

        $projects = $client->projects;

        return inertia(
            'Client/Show',
            [
                'client' => $client,
                'projects' => $projects
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $conversion_sources = ConversionSource::query()->get();

        return inertia(
            'Client/Create',
            [
                'conversionSources' => $conversion_sources
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client = $request->user()->clients()->create(
            RequestProcessor::validation($request, $this->fields, new Client())
        );

        if(request()->route()->getName() == 'client.create'){
            return redirect()->route('client.index')->with('success', 'Klient został dodany!');
        }

        return back()->with('inertia', $client);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $this->authorize('edit', $client);

        $conversion_sources = ConversionSource::query()->get();

        return inertia(
            'Client/Edit',
            [
                'client' => $client,
                'conversionSources' => $conversion_sources
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $client->update(
            RequestProcessor::validation($request, $this->fields, $client)
        );

        return redirect()->route('restore.state', ['url' => route('client.index')])->with('success', 'Klient został edytowany!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $client->deleteOrFail();

        return redirect()->back()->with('success', 'Klient został usunięty!');
    }

    public function restore(Client $client){
        $this->authorize('restore', $client);

        $client->restore();

        return redirect()->back()->with('success', 'Klient został odzyskany!');
    }
}
