<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $filters = $request->only([
            'priceFrom', 'priceTo', 'make', 'engine', 'kmFrom', 'kmTo'
        ]);

        $listings = Listing::mostRecent()
            ->filter($filters)
            ->paginate(10)
            ->withQueryString();

        return response()->json($listings);
    }

    public function getAvailableMakes()
    {
        $makes = Listing::select('make')
            ->distinct()
            ->orderBy('make', 'ASC')
            ->pluck('make');

        return response()->json($makes);
    }

    public function getAvailableEngines()
    {
        $engineTypes = Listing::select('engine_type')
            ->distinct()
            ->orderBy('engine_type', 'ASC')
            ->pluck('engine_type');

        return response()->json($engineTypes);
    }

    public function show(Listing $listing)
    {
        // if (Auth::user()->cannot('view', $listing)) {
        //     return response()->json(['error' => 'Not authorized.'],403);
        // }
        // $this->authorize('view', $listing);
        $listing->load('images');

        return response()->json($listing);
    }
}
