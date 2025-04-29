<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Update the status of a user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Update the status of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|string|in:Approved,Pending,Rejected',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update the user's status
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */

     public function update(Request $request, User $user)
     {
         // Validate the request
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
             'status' => 'nullable|string',
         ]);

         // Update the user
         $user->update($request->only(['name', 'email', 'status']));

         return response()->json([
             'message' => 'User updated successfully',
             'user' => $user
         ]);
     }

    public function index()
    {
        // Get all users
        $users = User::all();
        return response()->json($users);
    }

     public function pending()
    {
        // Get all pending users
        $users = User::where('status', 'Pending')->get();
        return response()->json($users);
    }

    // Get all approved users
    public function approved()
    {
        // Get all approved users
        $users = User::where('status', 'Approved')->get();
        return response()->json($users);
    }

    public function store(Request $request) // Create a new user
    {
   
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function login(Request $request) // Login a user
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'status' => 'nullable|string',
        ]);

    
        $user = User::where('email', $request->email)->first();

     
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

     
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function changePassword(Request $request) 
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // Ensure password confirmation
        ]);

        // Find the user by name
        $user = User::where('name', $request->name)->first();

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}
