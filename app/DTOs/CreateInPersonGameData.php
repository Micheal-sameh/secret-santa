<?php

namespace App\DTOs;

use App\Http\Requests\StoreInPersonGameRequest;
use App\Http\Requests\StoreInPersonGameApiRequest;

readonly class CreateInPersonGameData
{
    public function __construct(
        public string  $name,
        public ?float  $priceLimit,
        /** @var string[] */
        public array   $participantNames,
    ) {}

    public static function fromRequest(StoreInPersonGameRequest|StoreInPersonGameApiRequest $request): self
    {
        return new self(
            name:             $request->validated('name'),
            priceLimit:       $request->validated('price_limit') !== null
                                  ? (float) $request->validated('price_limit')
                                  : null,
            participantNames: $request->validated('participants'),
        );
    }
}
