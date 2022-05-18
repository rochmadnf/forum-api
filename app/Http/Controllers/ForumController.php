<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\UserNotDefinedException;

class ForumController extends Controller
{

  public function __construct()
  {
    return auth()->shouldUse('api');
  }

  public function index()
  {
    //
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'string|required|min:5',
      'body' => 'required|min:10',
      'category' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->messages());
    }


    try {
      $user = auth()->userOrFail();
    } catch (UserNotDefinedException $e) {
      return response()->json(['message' => 'not authenticated, you have to login first'], 200);
    }

    $user->forums()->create([
      'title' => $request->title,
      'body' => $request->body,
      'slug' => str($request->title)->slug() . '-' . str()->random(5),
      'category' => $request->category,
    ]);

    return response()->json(['message' => 'Successfully Posted.']);
  }

  public function show($id)
  {
    //
  }

  public function update(Request $request, $id)
  {
    //
  }

  public function destroy($id)
  {
    //
  }
}
