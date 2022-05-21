<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Traits\AuthTrait;
use App\Models\ForumComment;
use Illuminate\Support\Facades\Validator;

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

    return response()->json(['message' => 'Successfully comment posted']);
  }

  public function update(Request $request, $forumId, $commentId)
  {
    $this->validateRequest($request);
    $forumComment = ForumComment::findOrFail($commentId);

    $this->checkOwnership($forumComment->user_id);

    $forumComment->update([
      'body' => $request->body,
    ]);

    return response()->json(['message' => 'Successfully comment updated']);
  }

  public function destroy($forumId, $commentId)
  {
    $forumComment = ForumComment::findOrFail($commentId);
    $this->checkOwnership($forumComment->user_id);

    $forumComment->delete();

    return response()->json(['message' => 'Successfully comment deleted']);
  }

  private function validateRequest($request)
  {
    $validator =  Validator::make($request->all(), [
      'body' => 'required|min:10',
    ]);

    if ($validator->fails()) {
      response()->json($validator->messages())->send();
      exit;
    }
  }
}
