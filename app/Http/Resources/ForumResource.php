<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'title' => ucwords($this->title),
      'slug' => $this->slug,
      'body' => $this->body,
      'category' => $this->category,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'user' => $this->user,
      'comments' => $this->comments,
    ];
  }
}
