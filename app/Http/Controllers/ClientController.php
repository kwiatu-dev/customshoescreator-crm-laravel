<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = Client::getFilterFields($request);
        $sort = Client::getSortFields($request);

        $clients = Client::query()
            ->filter($filters)
            ->sort($sort)
            ->latest()
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
        return inertia(
            'Client/Create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->user()->clients()->create(
            $this->validation($request)
        );

        return redirect()->route('client.index')->with('success', 'Client was created!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        if(url()->previous() !== url()->current())
            session()->put('back_to_url', url()->previous());

        return inertia(
            'Client/Edit',
            [
                'client' => $client,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $client->update(
            $this->validation($request, $client)
        );

        $back_to_url = session()->pull('back_to_url');

        if($back_to_url)
            return redirect($back_to_url)->with('success', 'Client was changed!');
        else
            return redirect()->route('client.index')->with('success', 'Client was changed!');
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

    private function validation(Request $request, Client $client = null): array{
        return $request->validate([
            'first_name' => 'required|string|min:3|max:50', 
            'last_name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:clients,email' . ($client ? ",$client->id" : ''),
            'phone' => 'required|regex:/\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}/|unique:clients,phone' . ($client ? ",$client->id" : ''),
            'street' => 'nullable|string|min:3|max:50',
            'street_nr' => 'nullable|string|min:1|max:10',
            'apartment_nr' => 'nullable|string|min:1|max:10',
            'postcode' => 'nullable|string|min:3|max:10',
            'city' => 'nullable|string|min:3|max:25',
            'country' => 'nullable|string|min:3|max:25',
            'username' => 'nullable|string|min:3|max:30',
            'conversion_source' => 'nullable|string|in:google,instagram,facebook,tiktok,olx,allegro,znajomi',
            'social_link' => 'nullable|url:http,https|max:255'
        ]);
    }
}
