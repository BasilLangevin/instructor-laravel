<?php

namespace BasilLangevin\InstructorLaravel;

use BasilLangevin\InstructorLaravel\Concerns\ConfiguresProvider;
use BasilLangevin\InstructorLaravel\Concerns\ValidatesResponse;
use EchoLabs\Prism\Structured\PendingRequest;
use Illuminate\Support\Collection;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Validator as SchemaValidator;
use Spatie\LaravelData\Data;

class Instructor
{
    use ConfiguresProvider;
    use ValidatesResponse;

    /** @var class-string<Data> */
    protected string $schema;

    protected SchemaAdapter $adapter;

    public function __construct(
        protected PendingRequest $request,
        protected SchemaValidator $schemaValidator,
        protected ErrorFormatter $errorFormatter,
    ) {}

    public static function make(): self
    {
        return app(Instructor::class);
    }

    /** @param  class-string<Data>  $schema */
    public function withSchema(string $schema): self
    {
        $this->schema = $schema;
        $this->adapter = SchemaAdapter::make($schema);

        $this->request->withSchema($this->adapter);

        return $this;
    }

    /**
     * @return Data|Collection<int, Data>
     */
    public function generate(): Data|Collection
    {
        $this->ensureProviderIsSet();

        $response = $this->request->generate();

        $this->validateResponse($response);

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
