<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Traits\AuthTrait;
use App\Http\Resources\ForumResource;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ForumController extends Controller
{
  use AuthTrait;

  public function __construct()
  {
    return auth()->shouldUse('api');
  }

  public function index()
  {
    return ForumResource::collection(Forum::with(['user:id,username'])->paginate(5));
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
    return new ForumResource(Forum::with(['user:id,username', 'comments.user:id,username'])->findOrFail($id));
  }

  public function update(Request $request, $id)
  {
    $this->validateRequest($request);
    $forum = Forum::find($id);

    // Check ownership
    $this->checkOwnership($forum->user_id);

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
    // Check ownership
    $this->checkOwnership($forum->user_id);

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
}
