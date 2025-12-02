<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'giver_participant_id' => $this->whenLoaded('giver', $this->giver->name),
            // 'recipient_participant_id' => $this->whenLoaded('recipient', $this->recipient->name),
            'giver' => ParticipantResource::make($this->whenLoaded('giver')),
            'recipient' => ParticipantResource::make($this->whenLoaded('recipient')),
        ];
    }
}
