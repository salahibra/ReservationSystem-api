<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use \Illuminate\Support\Facades\Validator;


class PassportAuthController extends Controller
{
    // register api
    public function register(Request $request)
    {
        Validator::validate($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        // if user created already exists return a message
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['success' => 0, 'message' => 'User with this email already exists'], 401);
        }
        else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'student',
            ]);
            $token = $user->createToken($request->email)->accessToken;
            return response()->json(['success' => 1, 'message' => 'User registered successfully', 'token' => $token, 'user' => $user], 200);
        }
    }
    // login api
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken($request->email)->accessToken;
            // return token and user details(with role)
            return response()->json(['success' => 1, 'message' => 'Login successful', 'token' => $token, 'user' => auth()->user()], 200);
        } else {
            return response()->json(['success' => 0, 'message' => 'Login failed'], 401);
        }
    }
    // user details api
    public function userDetails()
    {
        $user = auth()->user();
        return response()->json(['user' => $user], 200);
    }
    // get all users except the user who has admin role
    public function getUsers()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return response()->json(['users' => $users], 200);
    }
}
