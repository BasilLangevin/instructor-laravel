<?php

namespace BasilLangevin\InstructorLaravel;

use BasilLangevin\InstructorLaravel\Concerns\ConfiguresProvider;
use BasilLangevin\InstructorLaravel\Concerns\InitializesTraits;
use BasilLangevin\InstructorLaravel\Concerns\ManagesMessages;
use BasilLangevin\InstructorLaravel\Concerns\RetriesRequest;
use BasilLangevin\InstructorLaravel\Concerns\ValidatesResponse;
use BasilLangevin\InstructorLaravel\Exceptions\SchemaValidationException;
use EchoLabs\Prism\Structured\PendingRequest;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class Instructor
{
    use ConfiguresProvider;
    use InitializesTraits;
    use ManagesMessages;
    use RetriesRequest;
    use ValidatesResponse;

    /** @var class-string<Data> */
    protected string $schema;

    protected SchemaAdapter $adapter;

    public function __construct(
        protected PendingRequest $request,
    ) {
        $this->initializeTraits();
    }

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

        return $this->retry(function () {
            $response = $this->request->generate();

            try {
                $this->validateResponse($response);
            } catch (SchemaValidationException $e) {
                $this->addRetryMessages($response, $e);
                throw $e;
            }

            return $this->schema::from($response->structured);
        });
    }

    /**
     * Pass inaccessible method calls to the underlying Prism instance.
     */
    public function __call(mixed $method, mixed $arguments): mixed
    {
        return $this->request->$method(...$arguments);
    }
}
