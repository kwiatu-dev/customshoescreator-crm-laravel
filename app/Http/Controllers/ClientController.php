<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\Client;
use App\Models\ConversionSource;
use App\Models\User;
use App\Notifications\Client\ClientCreateNotification;
use App\Notifications\Client\ClientDeleteNotification;
use App\Notifications\Client\ClientRestoreNotification;
use App\Notifications\Client\ClientUpdateNotification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $fields;

    public function __construct(
        private NotificationService $notificationService,
    )
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

        $client->append([
            'editable',
            'deletable',
            'restorable',
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

        $this->notificationService->sendNotification(
            new ClientCreateNotification($client, $request->user(), $client->user),
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

        $this->notificationService->sendNotification(
            new ClientUpdateNotification($client, $request->user(), $client->user),
        );

        return redirect()->route('restore.state', ['url' => route('client.index')])->with('success', 'Klient został edytowany!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client, Request $request)
    {
        $this->authorize('delete', $client);

        $client->deleteOrFail();

        $this->notificationService->sendNotification(
            new ClientDeleteNotification($client, $request->user(), $client->user),
        );

        return redirect()->back()->with('success', 'Klient został usunięty!');
    }

    public function restore(Client $client, Request $request)
    {
        $this->authorize('restore', $client);

        $client->restore();

        $this->notificationService->sendNotification(
            new ClientRestoreNotification($client, $request->user(), $client->user),
        );

        return redirect()->back()->with('success', 'Klient został odzyskany!');
    }
}
