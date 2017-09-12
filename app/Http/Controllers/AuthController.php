<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use Purifier;
use Hash;
use Auth;
use JWTAuth;
use App\User;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.auth', ['only' => ['getUser']]);
  }

  public function getUser()
  {
    $id = Auth::id();
    $user = User::find($id);

    return Response::json(['user' => $user]);
  }

  public function signUp(Request $request)
  {
    $rules = [
      'email' => 'required',
      'name' => 'required',
      'password' => 'required'
    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);
    if($validator->fails())
    {
      return Response::json(['error' => 'Please fill out all fields.']);
    }
    $email = $request->input('email');
    $name = $request->input('name');
    $password = $request->input('password');

    $password = Hash::make($password);

    $user = new User;
    $user->email = $email;
    $user->name = $name;
    $user->password = $password;
    $user->roleID = 1;
    $user->save();

    return Response::json(['success' => 'Sign up successful!']);
  }

  public function signIn(Request $request)
  {
    $rules = [
      'email' => 'required',
      'password' => 'required'
    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);

    if($validator->fails())
    {
      return Response::json(['error' => 'Please fill out all fields.']);
    }

    $email = $request->input('email');
    $password = $request->input('password');
    $credentials = compact('email', 'password');

    $token = JWTAuth::attempt($credentials);

    if($token == false)
    {
      return Response::json(['error' => 'Sorry, wrong email or password.']);
    }
    else
    {
      return Response::json(['token' => $token]);
    }
  }
}
