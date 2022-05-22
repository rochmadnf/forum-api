<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'username' => $this->username,
      'created_at' => $this->created_at,
      'activity' => [
        'forums' => $this->forums,
        'comments' => $this->forumComments,
      ]
    ];
  }
}
