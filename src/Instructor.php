<?php

namespace BasilLangevin\InstructorLaravel;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use EchoLabs\Prism\Structured\PendingRequest;
use Spatie\LaravelData\Data;

class Instructor
{
    public function __construct(
        protected PendingRequest $request,
    ) {}

    public static function make(): self
    {
        return app(Instructor::class);
    }

    /** @param  class-string<Data>|array<string, mixed>|Schema  $schema */
    public function withSchema(string|array|Schema $schema): self
    {
        $this->request->withSchema(SchemaAdapter::make($schema));

        return $this;
    }

    /**
     * Pass inaccessible method calls to the underlying Prism instance.
     */
    public function __call(mixed $method, mixed $arguments): mixed
    {
        return $this->request->$method(...$arguments);
    }
}
