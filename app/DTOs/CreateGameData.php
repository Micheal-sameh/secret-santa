<?php

namespace App\DTOs;

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\StoreGameApiRequest;
use Carbon\Carbon;

readonly class CreateGameData
{
    public function __construct(
        public string  $name,
        public ?float  $priceLimit,
        public Carbon  $endDate,
        public Carbon  $meetingDate,
    ) {}

    public static function fromRequest(StoreGameRequest|StoreGameApiRequest $request): self
    {
        return new self(
            name:        $request->validated('name'),
            priceLimit:  $request->validated('price_limit') !== null
                            ? (float) $request->validated('price_limit')
                            : null,
            endDate:     Carbon::parse($request->validated('end_date')),
            meetingDate: Carbon::parse($request->validated('meeting_date')),
        );
    }

    public function toArray(): array
    {
        return [
            'name'         => $this->name,
            'price_limit'  => $this->priceLimit,
            'end_date'     => $this->endDate,
            'meeting_date' => $this->meetingDate,
        ];
    }
}
