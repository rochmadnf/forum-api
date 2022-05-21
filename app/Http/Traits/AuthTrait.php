<?php

namespace App\Http\Traits;

use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Exceptions\UserNotDefinedException;

trait AuthTrait
{
  private function getAuthUser()
  {
    try {
      return auth()->userOrFail();
    } catch (UserNotDefinedException $e) {
      response()->json(['message' => 'not authenticated, you have to login first'], 405)->send();
      exit;
    }
  }

  private function checkOwnership($forum)
  {
    $user = $this->getAuthUser();

    if ($user->id !== $forum) {
      response()->json(['message' => 'Not Authorized'], Response::HTTP_UNAUTHORIZED)->send();
      exit;
    }
  }
}
