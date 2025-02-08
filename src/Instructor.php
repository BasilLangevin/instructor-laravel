<?php

namespace BasilLangevin\InstructorLaravel;

use EchoLabs\Prism\Structured\PendingRequest;

class Instructor
{
    public function __construct(protected PendingRequest $request,
    ) {}

    public static function make(): self
    {
        return app(Instructor::class);
    }

    /**
     * Pass inaccessible method calls to the underlying Prism instance.
     */
    public function __call(mixed $method, mixed $arguments): mixed
    {
        return $this->request->$method(...$arguments);
    }
}
