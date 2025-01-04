<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CarDealerController extends Controller
{
    use AuthorizesRequests;

    // public function __construct()
    // {
    //     $this->authorizeResource(Listing::class, 'listing');
    // }

    public function index(Request $request)
    {
        $filters = [
            'deleted' => $request->boolean('deleted'),
            ...$request->only(['by', 'order'])
        ];

        $carDealerListings = Auth::user()
            ->listings()
            ->filter($filters)
            ->withCount('images')
            ->withCount('offers')
            ->paginate(5)
            ->withQueryString();

        return response()->json($carDealerListings);
    }

    public function show($id)
    {
        $listing = Listing::find($id);
        $listing->load('offers', 'offers.bidder');

        return response()->json([
            'listing' => $listing,
        ]);
    }

    public function store(Request $request)
    {

        $request->user()->listings()->create(
            $request->validate([
                'category' => 'required|string',
                'make' => 'required|string',
                'model' => 'required|string',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                'engine_type' => 'nullable|string',
                'horsepower' => 'nullable|integer',
                'total_kilometers' => 'nullable|integer',
                'color' => 'required|string',
                'city' => 'required|string',
                'price' => 'required|integer|min:0',
            ])
        );
        
        return response()->json([
            'message' => 'Listing created successfully!'
        ]);
    }

    public function edit($id)
    {
        $listing = Listing::findOrFail($id);

        return response()->json($listing);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'engine_type' => 'nullable|string',
            'horsepower' => 'nullable|integer',
            'total_kilometers' => 'nullable|integer',
            'color' => 'required|string',
            'city' => 'required|string',
            'price' => 'required|integer|min:0',
        ]);

        $listing = Listing::findOrFail($id);
        $listing->update($validated);

        return response()->json([
            'message' => 'Listing updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        $listing->delete();

        return response()->json([
            'message' => 'Listing deleted successfully'
        ]);
    }

    public function restore($id)
    {
        $listing = Listing::withTrashed()->findOrFail($id);
        $listing->restore();

        return response()->json([
            'message' => 'Listing restored successfully!'
        ]);
    }
}
