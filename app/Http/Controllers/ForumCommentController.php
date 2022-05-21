<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\AuthTrait;
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

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
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
