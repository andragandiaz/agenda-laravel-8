<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;

class AuthController extends Controller
{
    /**
     * Register a new user
     * 
     * This endpoint allows a user to register a new account in the system.
     * 
     * @group Authentication
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
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
     * User login
     * 
     * This endpoint allows a user to login with an email and password.
     * 
     * @group Authentication
     * 
     * @bodyParam email string required The email address of the user. Example: andra@yahoo.com
     * @bodyParam password string required The password for the account. Example: rahasia123
     * 
     * @response {
     *   "success": true,
     *   "message": "Login sukses",
     *   "data": {
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *     "name": "Andra",
     *     "email": "andra@yahoo.com"
     *   }
     * }
     * 
     * @response 401 {
     *   "success": false,
     *   "message": "Cek email dan password lagi",
     *   "data": null
     * }
     */
    public function login(Request $request)
    {
        // Gunakan data berikut untuk login: email: "andra@yahoo.com", password: "rahasia123"
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['email'] = $auth->email;

            return response()->json([
                'success' => true,
                'message' => 'Login sukses',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cek email dan password lagi',
                'data' => null
            ]);
        }
    }
}
