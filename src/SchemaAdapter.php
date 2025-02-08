<?php

namespace BasilLangevin\InstructorLaravel;

use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use EchoLabs\Prism\Contracts\Schema as PrismSchema;
use Spatie\LaravelData\Data;

class SchemaAdapter implements PrismSchema
{
    public function __construct(
        /** @var array<string, mixed> */
        protected array $schema,
    ) {}

    /** @param  class-string<Data>|array<string, mixed>|Schema  $schema */
    public static function make(string|array|Schema $schema): self
    {
        return match (true) {
            is_string($schema) => new self(JsonSchema::toArray($schema)),
            is_array($schema) => new self($schema),
            default => new self($schema->toArray()),
        };
    }

    /**
     * Since this is a root schema, it doesn't have a name.
     */
    public function name(): string
    {
        return '';
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->schema;
    }
}
