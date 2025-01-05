<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Notifications\OfferMade;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingOfferController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $listing = Listing::find($request->listing_id);

        $this->authorize('view', $listing);

        $offer = $listing->offers()->save(
            Offer::make(
                $request->validate([
                    'amount' => 'required|integer|min:1|max:20000000'
                ])
            )->bidder()->associate($request->user())
        );
        
        $listing->owner->notify(
            new OfferMade($offer)
        );

        return response()->json([
            'message' => 'Offer was made!'
        ]);
    }
}
