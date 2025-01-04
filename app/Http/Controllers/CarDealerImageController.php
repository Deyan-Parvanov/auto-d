<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarDealerImageController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'images.*' => 'mimes:jpg,jpegpng,webp|max:5000'
        ], [
            'images.*.mimes' => 'The file should be in one of the formats: jpg, png, jpeg, webp'
        ]);

        if ($request->hasFile('images')) {
            $listing = Listing::findOrFail($id);

            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $listing->images()->save(new ListingImage([
                    'filename' => $path
                ]));
            }
        }
        
        return response()->json([
            'message' => 'Images uploaded successfully!',
        ]);
    }

    public function destroy($id)
    {
        $image = ListingImage::findOrFail($id);

        Storage::disk('public')->delete($image->filename);
        $image->delete();

        return response()->json([
            'message' => 'Image was deleted!',
        ]);
    }
}
