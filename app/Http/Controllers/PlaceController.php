<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    // List all places
    public function index()
    {
        $places = Place::all();                                     // returns Eloquent Collection :contentReference[oaicite:5]{index=5}
        return response()->json($places);
    }

    // List approved places for carousel
    public function carousel()
    {
        $places = Place::where('status', 'Approved')->get();
        return response()->json($places);
    }

    // List pending places
    public function pending()
    {
        $places = Place::where('status', 'Pending')->get();
        return response()->json($places);
    }

    // Store a new place
    public function store(Request $request)
    {
        // 1. Validate including new fields
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'place_name'     => 'required|string|max:255',
            'address'        => 'required|string',
            'email_address'  => 'nullable|email',
            'contact_no'     => 'nullable|string',
            'description'    => 'nullable|string',
            'virtual_iframe' => 'nullable|string',
            'map_iframe'     => 'nullable|string',
            'status'         => 'nullable|string|in:Pending,Approved,Rejected',
            'province'       => 'nullable|string|max:255',         // new :contentReference[oaicite:6]{index=6}
            'entrance_fee'   => 'nullable|numeric|min:0',          // new :contentReference[oaicite:7]{index=7}
            'activities'     => 'nullable|array',                  // new :contentReference[oaicite:8]{index=8}
            'activities.*'   => 'string',                          // each element must be string :contentReference[oaicite:9]{index=9}
            'services'       => 'nullable|array',                  // new :contentReference[oaicite:10]{index=10}
            'services.*'     => 'string',                          // each element must be string :contentReference[oaicite:11]{index=11}
            'image_link'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. Handle image upload on 'public' disk
        if ($request->hasFile('image_link') && $request->file('image_link')->isValid()) {
            $validated['image_link'] = $request->file('image_link')
                                           ->store('places', 'public'); // uses public disk :contentReference[oaicite:12]{index=12}
        }

        // 3. Create via mass-assignment (fillable)
        $place = Place::create($validated);                        // mass assignment uses $fillable :contentReference[oaicite:13]{index=13}

        return response()->json([
            'message' => 'Place created successfully',
            'place'   => $place,
        ], 201);
    }

    // Show a single place
    public function show(Place $place)
    {
        return response()->json($place);
    }

    // Update an existing place
    public function update(Request $request, Place $place)
    {
        // 1. Validate (same rules as store)
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'place_name'     => 'required|string|max:255',
            'address'        => 'required|string',
            'email_address'  => 'nullable|email',
            'contact_no'     => 'nullable|string',
            'description'    => 'nullable|string',
            'virtual_iframe' => 'nullable|string',
            'map_iframe'     => 'nullable|string',
            'status'         => 'nullable|string|in:Pending,Approved,Rejected',
            'province'       => 'nullable|string|max:255',
            'entrance_fee'   => 'nullable|numeric|min:0',
            'activities'     => 'nullable|array',
            'activities.*'   => 'string',
            'services'       => 'nullable|array',
            'services.*'     => 'string',
            'image_link'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. If new image, delete old and store new
        if ($request->hasFile('image_link') && $request->file('image_link')->isValid()) {
            if ($place->image_link && Storage::disk('public')->exists($place->image_link)) {
                Storage::disk('public')->delete($place->image_link); // cleanup old file :contentReference[oaicite:14]{index=14}
            }
            $validated['image_link'] = $request->file('image_link')
                                             ->store('places', 'public');
        }

        // 3. Mass-update
        $place->update($validated);                                // uses $fillable :contentReference[oaicite:15]{index=15}

        return response()->json([
            'message' => 'Place updated successfully',
            'place'   => $place,
        ]);
    }

    // Delete a place
    public function destroy(Place $place)
    {
        if ($place->image_link && Storage::disk('public')->exists($place->image_link)) {
            Storage::disk('public')->delete($place->image_link);
        }
        $place->delete();
        return response()->json(['message' => 'Place deleted successfully']);
    }

    // Update only status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Approved,Pending,Rejected',
        ]);

        $place = Place::findOrFail($id);                           // throws 404 if missing :contentReference[oaicite:16]{index=16}

        $place->status = $request->input('status');
        $place->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'place'   => $place,
        ]);
    }
}
