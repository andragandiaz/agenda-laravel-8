<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;

class UserController extends Controller
{
    /**
     * Add a new user
     * 
     * This endpoint allows an admin to add a new user to the system.
     * 
     * @group User Management
     * 
     * @bodyParam name string required The name of the user. Example: Andra
     * @bodyParam email string required The email address of the user. Example: andra@yahoo.com
     * @bodyParam password string required The password for the account. Example: rahasia123
     * @bodyParam confirm_password string required Password confirmation, must match the password. Example: rahasia123
     * 
     * @response {
     *   "success": true,
     *   "message": "Sukses register",
     *   "data": {
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *     "name": "Andra"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Ada kesalahan",
     *   "data": {
     *     "email": [
     *       "The email has already been taken."
     *     ],
     *     "password": [
     *       "The password field is required."
     *     ]
     *   }
     * }
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Sukses register',
            'data' => $success
        ]);
    }

    /**
     * List all users
     * 
     * This endpoint retrieves a list of all users in the system.
     * 
     * @group User Management
     * 
     * @response {
     *   "success": true,
     *   "message": "Daftar Pengguna",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Andra",
     *       "email": "andra@yahoo.com"
     *     }
     *   ]
     * }
     */
    public function list()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Pengguna',
            'data' => $users
        ]);
    }

    /**
     * Get details of a specific user
     * 
     * This endpoint retrieves details of a specific user by their ID.
     * 
     * @group User Management
     * 
     * @urlParam id integer required The ID of the user. Example: 1
     * 
     * @response {
     *   "success": true,
     *   "message": "Detail Pengguna",
     *   "data": {
     *     "id": 1,
     *     "name": "Andra",
     *     "email": "andra@yahoo.com"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Pengguna tidak ditemukan"
     * }
     */
    public function detail($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Pengguna',
            'data' => $user
        ]);
    }

    /**
     * Update a specific user
     * 
     * This endpoint allows an admin to update the details of a specific user.
     * 
     * @group User Management
     * 
     * @urlParam id integer required The ID of the user. Example: 1
     * @bodyParam name string The name of the user. Example: Andra
     * @bodyParam email string The email address of the user. Example: andra@yahoo.com
     * @bodyParam password string The new password for the account (if updating). Example: rahasia123
     * @bodyParam confirm_password string Password confirmation, must match the new password. Example: rahasia123
     * 
     * @response {
     *   "success": true,
     *   "message": "Pengguna berhasil diperbarui",
     *   "data": {
     *     "id": 1,
     *     "name": "Andra",
     *     "email": "andra@yahoo.com"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Ada kesalahan",
     *   "data": {
     *     "email": [
     *       "The email has already been taken."
     *     ]
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Pengguna tidak ditemukan"
     * }
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'sometimes|required|string|max:255',
            // 'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$id,
            // 'password' => 'sometimes|required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ]);
        }

        // Update user data, including password if provided
        $user->update([
            'name' => $request->get('name', $user->name),
            'email' => $request->get('email', $user->email),
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
            'data' => $user
        ]);
    }

    /**
     * Delete a specific user
     * 
     * This endpoint allows an admin to delete a specific user from the system.
     * 
     * @group User Management
     * 
     * @urlParam id integer required The ID of the user. Example: 1
     * 
     * @response {
     *   "success": true,
     *   "message": "Pengguna berhasil dihapus"
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Pengguna tidak ditemukan"
     * }
     */
    public function delete($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ]);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus',
        ]);
    }
}
