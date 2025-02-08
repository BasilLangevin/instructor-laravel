<?php

namespace BasilLangevin\InstructorLaravel;

use EchoLabs\Prism\Structured\PendingRequest;
use Spatie\LaravelData\Data;

class Instructor
{
    /** @var class-string<Data> */
    protected string $schema;

    public function __construct(
        protected PendingRequest $request,
    ) {}

    public static function make(): self
    {
        return app(Instructor::class);
    }

    /** @param  class-string<Data>  $schema */
    public function withSchema(string $schema): self
    {
        $this->schema = $schema;

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
