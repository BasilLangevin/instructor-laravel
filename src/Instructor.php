<?php

namespace BasilLangevin\InstructorLaravel;

use EchoLabs\Prism\Structured\PendingRequest;

class Instructor
{
    public function __construct(
        protected PendingRequest $prism,
    ) {}

    public static function make(): self
    {
        return app(Instructor::class);
    }
}
