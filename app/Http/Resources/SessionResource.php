<?php

namespace App\Http\Resources;

use App\Enums\SessionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'description' => $this->description,
            'code' => $this->code,
            'status' => new EnumResource($this->status, SessionStatus::class),
            'expires_at' => $this->expires_at->format('Y-m-d'),
            'shareable_link' => $this->shareable_link,
            'participants_count' => $this->whenLoaded('participants', $this->participants_count),
            'created_by' => new UserResource($this->whenLoaded('user')),
            'participants' => ParticipantResource::collection($this->whenLoaded('participants')),
        ];
    }
}
