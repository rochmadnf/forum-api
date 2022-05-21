<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\UserNotDefinedException;
use Symfony\Component\HttpFoundation\Response;

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
    $this->validateRequest($request);
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
    $this->validateRequest($request);
    $user = $this->getAuthUser();

    $forum = Forum::find($id);

    // Check ownership
    $this->checkOwnership($user->id, $forum->user_id);

    $forum->update([
      'title' => $request->title,
      'body' => $request->body,
      'category' => $request->category,
    ]);

    return response()->json(['message' => 'Successfully Updated.']);
  }

  public function destroy($id)
  {
    $forum = Forum::findOrFail($id);
    $user = $this->getAuthUser();
    // Check ownership
    $this->checkOwnership($user->id, $forum->user_id);

    $forum->delete();
    return response()->json(['message' => 'Successfully Deleted'], Response::HTTP_OK);
  }

  private function validateRequest($request)
  {
    $validator =  Validator::make($request->all(), [
      'title' => 'string|required|min:5',
      'body' => 'required|min:10',
      'category' => 'required',
    ]);

    if ($validator->fails()) {
      response()->json($validator->messages())->send();
      exit;
    }
  }

  private function getAuthUser()
  {
    try {
      return auth()->userOrFail();
    } catch (UserNotDefinedException $e) {
      response()->json(['message' => 'not authenticated, you have to login first'], 405)->send();
      exit;
    }
  }

  private function checkOwnership($user, $forum)
  {
    if ($user !== $forum) {
      response()->json(['message' => 'Not Authorized'], Response::HTTP_UNAUTHORIZED)->send();
      exit;
    }
  }
}
