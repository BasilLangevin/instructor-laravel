<?php

namespace BasilLangevin\InstructorLaravel\Tests\Support\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class FlockData extends Data
{
    public function __construct(
        public int $count,
        #[DataCollectionOf(BirdData::class)]
        public Collection $birds,
    ) {}
}
