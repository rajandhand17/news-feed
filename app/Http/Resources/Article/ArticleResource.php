<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          =>$this->id,
            'title'       =>$this->description,
            'url'         =>$this->url,
            'published_at'=>$this->published_at,
            'category'    =>$this->category,
            'source'      =>$this->source
        ];
    }
}
