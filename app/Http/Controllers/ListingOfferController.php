<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Listing $listing, Request $request){
        $offer = Offer::make(
            $request->validate([
                'amount' => 'required|integer|min:1|max:20000000'
            ])
        )->bidder()->associate($request->user());

        $listing->offers()->save($offer);

        return redirect()->back()->with('success', 'Offer was made!');
    }
}
