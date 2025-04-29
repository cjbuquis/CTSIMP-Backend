<?php
namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    /**
     * Display a listing of the places.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all places
        $places = Place::all();
        return response()->json($places);
    }


    public function carousel()
{
   
    $places = Place::where('status', 'Approved')->get();

    return response()->json($places);
}

public function pending()
{
   
    $places = Place::where('status', 'Pending')->get();

    return response()->json($places);
}

    /**
     * Store a newly created place in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request, including status
        $request->validate([
            'name' => 'required|string|max:255',
            'place_name' => 'required|string|max:255',
            'address' => 'required|string',
            'email_address' => 'nullable|email',
            'contact_no' => 'nullable|string',
            'description' => 'nullable|string',
            'virtual_iframe' => 'nullable|string',
            'map_iframe' => 'nullable|string',
            'image_link' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'status' => 'nullable|string',
            'entrance' => 'nullable|string',
            'room_or_cottages_price' => 'nullable|string',
            'history' => 'nullable|string',
            'activities' => 'nullable|string',
            'reason_for_rejection' => 'nullable|string',

        ]);


        $imageLink = null;
        if ($request->hasFile('image_link') && $request->file('image_link')->isValid()) {
    
            $imageLink = $request->file('image_link')->store('places', 'public');
        }

   
        $status = $request->input('status', 'active');


        $place = Place::create(array_merge($request->all(), [
            'image_link' => $imageLink,
            'status' => $status,
        ]));

        return response()->json([
            'message' => 'Place created successfully',
            'place' => $place
        ], 201);
    }

    /**
     * Display the specified place.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Place $place)
    {
        return response()->json($place);
    }

    /**

     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Place $place)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'place_name' => 'required|string|max:255',
            'address' => 'required|string',
            'email_address' => 'nullable|email',
            'contact_no' => 'nullable|string',
            'description' => 'nullable|string',
            'virtual_iframe' => 'nullable|string',
            'map_iframe' => 'nullable|string',
            'image_link' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'nullable|string',
            'entrance' => 'nullable|string',
            'room_or_cottages_price' => 'nullable|string',
            'history' => 'nullable|string',
            'activities' => 'nullable|string',
            'reason_for_rejection' => 'nullable|string',

        ]);

     
        if ($request->hasFile('image_link') && $request->file('image_link')->isValid()) {
 
            if ($place->image_link && Storage::disk('public')->exists($place->image_link)) {
                Storage::disk('public')->delete($place->image_link);
            }

         
            $imageLink = $request->file('image_link')->store('places', 'public');
            $place->image_link = $imageLink;
        }


        $place->update($request->only(['name', 'place_name', 'address', 'email_address', 'contact_no', 'description', 'virtual_iframe', 'map_iframe', 'status', 'entrance', 'room_or_cottages_price', 'history', 'activities', 'reason_for_rejection']));

        return response()->json([
            'message' => 'Place updated successfully',
            'place' => $place
        ]);
    }

    /**
     * Remove the specified place from the database.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Place $place)
    {

        if ($place->image_link && Storage::disk('public')->exists($place->image_link)) {
            Storage::disk('public')->delete($place->image_link);
        }

  
        $place->delete();

        return response()->json([
            'message' => 'Place deleted successfully'
        ]);
    }

    public function updateStatus(Request $request, $id)
{
   
    $request->validate([
        'status' => 'required|string|in:Approved,Pending,Rejected', 
    ]);

    
    $place = Place::find($id);

 
    if (!$place) {
        return response()->json([
            'message' => 'Place not found'
        ], 404);
    }

 
    $place->status = $request->input('status');
    $place->save();

    return response()->json([
        'message' => 'Status updated successfully',
        'place' => $place
    ]);
}
}
