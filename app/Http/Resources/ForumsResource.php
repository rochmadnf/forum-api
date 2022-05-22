<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumsResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
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
      'comments_count' => $this->comments_count,
    ];
  }
}
