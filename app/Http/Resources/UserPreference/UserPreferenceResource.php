<?php

namespace App\Http\Resources\UserPreference;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        =>$this->id,
            'user_id'   =>$this->user_id,
            'sources'   =>$this->sources,
            'categories'=>$this->categoies,
            'authors'   =>$this->authors,
        ];
    }
}
