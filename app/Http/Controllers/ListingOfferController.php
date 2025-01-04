<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingOfferController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $listing = Listing::find($request->listing_id);

        $this->authorize('view', $listing);

        $listing->offers()->save(
            Offer::make(
                $request->validate([
                    'amount' => 'required|integer|min:1|max:20000000'
                ])
            )->bidder()->associate($request->user())
        );

        return response()->json([
            'message' => 'Offer was made!'
        ]);
    }
}
