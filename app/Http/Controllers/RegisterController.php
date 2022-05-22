<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
  public function signUp()
  {
    $validator = Validator::make(request()->all(), [
      'username' => 'required|unique:users',
      'email' => 'required|email|unique:users',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $user = User::create(
      [
        'username' => request('username'),
        'email' => request('email'),
        'password' => bcrypt(request('password')),
      ]
    );

    return response()->json(['message' => 'Successfully registered!']);
  }
}
