<?php

namespace BasilLangevin\InstructorLaravel\Tests\Support\Data;

use Spatie\LaravelData\Data;

class BirdData extends Data
{
    public function __construct(
        public string $species,
    ) {}
}
