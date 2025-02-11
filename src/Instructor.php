<?php

namespace BasilLangevin\InstructorLaravel;

use BasilLangevin\InstructorLaravel\Concerns\ConfiguresProvider;
use BasilLangevin\InstructorLaravel\Concerns\InitializesTraits;
use BasilLangevin\InstructorLaravel\Concerns\ManagesMessages;
use BasilLangevin\InstructorLaravel\Concerns\RetriesRequest;
use BasilLangevin\InstructorLaravel\Concerns\ValidatesResponse;
use BasilLangevin\InstructorLaravel\Exceptions\SchemaValidationException;
use EchoLabs\Prism\Structured\PendingRequest;
use EchoLabs\Prism\Structured\Response;
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

    protected bool $isCollection = false;

    /** @var class-string<Collection<int, Data>> */
    protected string $collectionClass = Collection::class;

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
        $this->isCollection = false;
        $this->adapter = SchemaAdapter::make($schema);

        $this->request->withSchema($this->adapter);

        return $this;
    }

    /**
     * @param  class-string<Data>  $schema
     * @param  class-string<Collection<int, Data>>  $collectionClass
     */
    public function withCollectionSchema(string $schema, string $collectionClass = Collection::class): self
    {
        $this->schema = $schema;
        $this->isCollection = true;
        $this->collectionClass = $collectionClass;
        $this->adapter = SchemaAdapter::makeCollection($schema);

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

            return $this->resolveResponse($response);
        });
    }

    /**
     * Transform a Prism response into a Data object or a collection of Data objects.
     *
     * @return Data|Collection<int, Data>
     */
    protected function resolveResponse(Response $response): Data|Collection
    {
        $data = $response->structured;

        if (! $this->isCollection) {
            return $this->schema::from($data);
        }

        /** @var array<string, mixed>[] $data */
        return $this->collectionClass::make($data)
            ->map(fn (array $data) => $this->schema::from($data));
    }

    /**
     * Pass inaccessible method calls to the underlying Prism instance.
     */
    public function __call(mixed $method, mixed $arguments): mixed
    {
        $result = $this->request->$method(...$arguments);

        if ($result === $this->request) {
            return $this;
        }

        return $result;
    }
}
