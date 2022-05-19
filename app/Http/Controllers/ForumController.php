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
    return Forum::with(['user:id,username'])->get();
  }

  public function store(Request $request)
  {
    $validator = $this->validateRequest($request);
    if ($validator->fails()) {
      return response()->json($validator->messages());
    }

    $user = $this->getAuthUser();
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
    return Forum::with(['user:id,username'])->findOrFail($id);
  }

  public function update(Request $request, $id)
  {
    $validator = $this->validateRequest($request);

    if ($validator->fails()) {
      return response()->json($validator->messages());
    }

    $user = $this->getAuthUser();

    // Check ownership

    Forum::find($id)->update([
      'title' => $request->title,
      'body' => $request->body,
      'category' => $request->category,
    ]);

    return response()->json(['message' => 'Successfully Updated.']);
  }

  public function destroy($id)
  {
    //
  }

  private function validateRequest($request)
  {
    return Validator::make($request->all(), [
      'title' => 'string|required|min:5',
      'body' => 'required|min:10',
      'category' => 'required',
    ]);
  }

  private function getAuthUser()
  {
    try {
      return auth()->userOrFail();
    } catch (UserNotDefinedException $e) {
      return response()->json(['message' => 'not authenticated, you have to login first'], 200);
    }
  }
}
