<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;
use Illuminate\Http\Request;
use App\Http\Traits\AuthTrait;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ForumCommentController extends Controller
{

  use AuthTrait;

  public function __construct()
  {
    return auth()->shouldUse('api');
  }

  public function store(Request $request, $forumId)
  {
    $this->validateRequest($request);
    $user = $this->getAuthUser();

    $user->forumComments()->create([
      'body' => $request->body,
      'forum_id' => $forumId,
    ]);

    return response()->json(['message' => 'Successfully comment posted'], Response::HTTP_OK);
  }

  public function update(Request $request, $forumId, $commentId)
  {
    $this->validateRequest($request);
    $forumComment = ForumComment::findOrFail($commentId);

    $this->checkOwnership($forumComment->user_id);

    $forumComment->update([
      'body' => $request->body,
    ]);

    return response()->json(['message' => 'Successfully comment updated'], Response::HTTP_OK);
  }

  public function destroy($forumId, $commentId)
  {
    $forumComment = ForumComment::findOrFail($commentId);
    $this->checkOwnership($forumComment->user_id);

    $forumComment->delete();

    return response()->json(['message' => 'Successfully comment deleted'], Response::HTTP_OK);
  }

  private function validateRequest($request)
  {
    $validator =  Validator::make($request->all(), [
      'body' => 'required|min:10',
    ]);

    if ($validator->fails()) {
      response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY)->send();
      exit;
    }
  }
}
