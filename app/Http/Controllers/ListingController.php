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
    
    public function index()
    {
        $listings = Listing::orderByDesc('created_at')
            ->paginate(10);

        return response()->json($listings);
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

    public function show(Listing $listing)
    {
        // if (Auth::user()->cannot('view', $listing)) {
        //     return response()->json(['error' => 'Not authorized.'],403);
        // }
        // $this->authorize('view', $listing);
        return response()->json($listing);
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
}
