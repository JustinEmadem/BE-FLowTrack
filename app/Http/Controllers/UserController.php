<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Spatie uses 'roles' (many-to-many)
            $query = User::with('roles');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Get all users without pagination for frontend
            $users = $query->get();
            
            // Transform data to match your frontend UserData interface
            $transformedUsers = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'middlename' => $user->middlename,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'bio' => $user->bio,
                    'is_active' => $user->is_active,
                    'role_id' => $user->roles->first()?->id,
                    // Create a 'role' object with the first role
                    'role' => $user->roles->first() ? [
                        'id' => $user->roles->first()->id,
                        'name' => $user->roles->first()->name,
                    ] : null,
                    // Keep the roles array too
                    'roles' => $user->roles->map(function($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                        ];
                    }),
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $transformedUsers
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            
            $transformedUser = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'middlename' => $user->middlename,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'bio' => $user->bio,
                'is_active' => $user->is_active,
                'role_id' => $user->roles->first()?->id,
                'role' => $user->roles->first() ? [
                    'id' => $user->roles->first()->id,
                    'name' => $user->roles->first()->name,
                ] : null,
                'roles' => $user->roles,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $transformedUser
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'firstname' => 'sometimes|string|max:255',
                'middlename' => 'nullable|string|max:255',
                'lastname' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'bio' => 'nullable|string',
                'is_active' => 'sometimes|boolean',
                'role_id' => 'sometimes|exists:roles,id', // For Spatie
                'role_name' => 'sometimes|string|exists:roles,name', // Alternative
            ]);
            
            // Update basic user info
            $user->update($validated);
            
            // Update role if provided (Spatie way)
            if ($request->has('role_id')) {
                $role = \Spatie\Permission\Models\Role::findOrFail($request->role_id);
                $user->syncRoles([$role->name]); // Spatie uses role names
            } elseif ($request->has('role_name')) {
                $user->syncRoles([$request->role_name]);
            }
            
            $user->load('roles');
            
            $transformedUser = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'middlename' => $user->middlename,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'bio' => $user->bio,
                'is_active' => $user->is_active,
                'role_id' => $user->roles->first()?->id,
                'role' => $user->roles->first() ? [
                    'id' => $user->roles->first()->id,
                    'name' => $user->roles->first()->name,
                ] : null,
                'roles' => $user->roles,
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $transformedUser
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}