<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'deleted' => $request->boolean('deleted'),
            ...$request->only(['search'])
        ];

        $sort = $request->only(['columns']);

        $clients = Client::latest()
            ->filter($filters)
            ->sort($sort)
            ->paginate(6)
            ->withQueryString();

        return inertia(
            'Client/Index',
            [
                'clients' => $clients,
                'filters' => $filters,
                'sort' => $sort,
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
    public function destroy(Client $client)
    {
        $client->deleteOrFail();

        return redirect()->back()->with('success', 'Client was deleted!');
    }

    public function restore(Client $client){
        $client->restore();

        return redirect()->back()->with('success', 'Client was restored!');
    }
}
