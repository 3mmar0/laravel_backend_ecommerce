<?php

namespace App\Http\Resources\Fron;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => config('app.url') . '/storage/' . $this->image,
        ];
        if (!$this->parent_id) {
            $data['children'] = CategoryResource::collection($this->children);
        }
        return $data;
    }
}
