<?php

use BasilLangevin\InstructorLaravel\SchemaAdapter;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use EchoLabs\Prism\Contracts\Schema as PrismSchema;

covers(SchemaAdapter::class);

it('adapts a json schema to a prism schema')
    ->expect(fn () => new SchemaAdapter(['type' => 'object']))
    ->toBeInstanceOf(PrismSchema::class);

it('has an empty name')
    ->expect(fn () => new SchemaAdapter(['type' => 'object']))
    ->name()
    ->toBe('');

it('can get the array representation of the schema')
    ->expect(fn () => new SchemaAdapter(['type' => 'object']))
    ->toArray()
    ->toBe(['type' => 'object']);

it('can make a schema adapter from a data class name', function () {
    $schema = SchemaAdapter::make(BirdData::class);

    expect($schema)
        ->toBeInstanceOf(SchemaAdapter::class)
        ->toArray()
        ->toEqual(JsonSchema::toArray(BirdData::class));
});

it('can make a schema adapter from an array')
    ->expect(fn () => SchemaAdapter::make(['type' => 'object']))
    ->toBeInstanceOf(SchemaAdapter::class)
    ->toArray()
    ->toBe(['type' => 'object']);

it('can make a schema adapter from a schema', function () {
    $schema = JsonSchema::make(BirdData::class);

    expect(SchemaAdapter::make($schema))
        ->toBeInstanceOf(SchemaAdapter::class)
        ->toArray()
        ->toEqual(JsonSchema::toArray(BirdData::class));
});
