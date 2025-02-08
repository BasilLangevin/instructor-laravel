<?php

namespace BasilLangevin\InstructorLaravel;

use BasilLangevin\InstructorLaravel\Concerns\ConfiguresProvider;
use EchoLabs\Prism\Structured\PendingRequest;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class Instructor
{
    use ConfiguresProvider;

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
     * @return Data|Collection<int, Data>
     */
    public function generate(): Data|Collection
    {
        $this->ensureProviderIsSet();

        $response = $this->request->generate();

        return $this->schema::from($response->structured);
    }

    /**
     * Pass inaccessible method calls to the underlying Prism instance.
     */
    public function __call(mixed $method, mixed $arguments): mixed
    {
        return $this->request->$method(...$arguments);
    }
}
