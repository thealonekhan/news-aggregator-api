<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'source' => $this->source,
            'author' => $this->author,
            'url' => $this->url,
            'category' => $this->category,
            'published_at' => Carbon::parse($this->published_at)->toDateTimeString(), // Keep formatted
        ];
    }
}