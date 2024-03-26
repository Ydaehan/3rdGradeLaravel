<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  // New member registration
  public function register(Request $request)
  {
    // User input validation
    $fields = $request->validate([
      'name' => 'required|string',
      'email' => 'required|string|email|unique:users',
      'password' => 'required|string|confirmed'
    ]);

    // Insert a new member record into uesrs table
    $user = User::create([
      'name' => $fields['name'],
      'email' => $fields['email'],
      'password' => bcrypt($fields['password'])
    ]);

    // generate an API token for this user
    $token = $user->createToken('myAppToken')->plainTextToken;

    $response = [
      'user' => $user,
      'token' => $token
    ];

    return response($response, 201);
  }

  public function login(Request $request)
  {
    // User input validations
    $validations = $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string'
    ]);

    // 1. Check whether the email exists in the users table
    //    If exist retrieve the record which contains the email value
    $user = User::where('email', $validations['email'])->first();

    // 2. Compare the password value with the record's password

    if (!$user || !Hash::check($validations['password'], $user->password)) {
      return response(['message' => 'Bad credentials'], 401);
    }

    // Generate an API token for this user
    $token = $user->createToken('myAppToken')->plainTextToken;
    $response = ['user' => $user, 'token' => $token];
    return response($response, 201);
  }

  public function logout(Request $request)
  {
    // log out this authenticated user using auth() helper function
    auth()->user()->token()->delete();
    return ['message' => 'Logged out'];
  }
}
