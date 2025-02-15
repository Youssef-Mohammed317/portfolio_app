<?php

namespace App\Http\Resources;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => asset('storage/projects/' . $this->image), // full url of the image
            'project_url' => $this->project_url,
            'skill_id' => $this->skill_id,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'skill' => [
                'id' => $this->skill->id,
                'name'=> $this->skill->name,
                'image' => asset('storage/skills/' . $this->skill->image),
                'created_at' => $this->skill->created_at->diffForHumans(),
                'updated_at' => $this->skill->updated_at->diffForHumans(),
            ],
            'user_id' => $this->user_id,
            'user' => $this->user
        ];
    }
}
