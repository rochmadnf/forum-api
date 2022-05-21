<?php

namespace App\Http\Traits;

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
}
