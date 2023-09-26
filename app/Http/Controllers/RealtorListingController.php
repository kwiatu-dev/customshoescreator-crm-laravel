<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RealtorListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Listing::class, 'listing');
    }

    public function index(Request $request){
        $filters = [
            'deleted' => $request->boolean('deleted'),
            ...$request->only(['by', 'order'])
        ];

        return inertia(
            'Realtor/Index',
            [
                'filters' => $filters,
                'listings' => Auth::user()
                    ->listings()
                    ->filter($filters)
                    ->withCount('images')
                    ->withCount('offers')
                    ->paginate(5)
                    ->withQueryString()
            ],
        );
    }

    public function show(Listing $listing){
        $listing->load('offers');

        return inertia(
            'Realtor/Show',
            [
                'listing' => $listing
            ]
            
        );
    }

    public function destroy(Listing $listing)
    {
        $listing->deleteOrFail();

        return redirect()->back()->with('success', 'Listing was deleted!');
    }

        /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        return inertia(
            'Realtor/Edit',
            [
                'listing' => $listing,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        $listing->update(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required|string|not_regex:/^\d+$/',
                'code' => 'required|string',
                'street' => 'required|string|not_regex:/^\d+$/',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000'
            ],
            [
                'city.not_regex' => 'The city cannot be a number!',
                'street.not_regex' => 'The street cannot be a number!',
            ])
        );

        return redirect()->route('realtor.listing.index')->with('success', 'Listing was changed!');
    }

        /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia(
            'Realtor/Create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->user()->listings()->create(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required|string|not_regex:/^\d+$/',
                'code' => 'required|string',
                'street' => 'required|string|not_regex:/^\d+$/',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000'
            ],
            [
                'city.not_regex' => 'The city cannot be a number!',
                'street.not_regex' => 'The street cannot be a number!',
            ])
        );

        return redirect()->route('realtor.listing.index')->with('success', 'Listing was created!');
    }

    public function restore(Listing $listing){
        $listing->restore();

        return redirect()->back()->with('success', 'Listing was restored!');
    }
}
