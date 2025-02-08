<?php

use BasilLangevin\InstructorLaravel\Facades\Instructor as InstructorFacade;
use BasilLangevin\InstructorLaravel\Instructor;
use BasilLangevin\InstructorLaravel\SchemaAdapter;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;

covers(Instructor::class);

beforeEach(function () {
    $this->instructor = InstructorFacade::make();

    $reflection = new ReflectionClass($this->instructor);
    $property = $reflection->getProperty('request');
    $property->setAccessible(true);

    $this->request = $property->getValue($this->instructor);

    $reflection = new ReflectionClass($this->request);
    $property = $reflection->getProperty('schema');
    $property->setAccessible(true);

    $this->getSchema = fn () => $property->getValue($this->request);
});

it('can be created via its facade')
    ->expect(fn () => InstructorFacade::make())
    ->toBeInstanceOf(Instructor::class);

it('can pass method calls to the underlying Prism instance', function () {
    $this->instructor->withPrompt('Hello, world!');

    $reflection = new ReflectionClass($this->request);
    $property = $reflection->getProperty('prompt');
    $property->setAccessible(true);

    expect($property->getValue($this->request))->toBe('Hello, world!');
});

describe('withSchema', function () {
    it('can set the schema to a Data class', function () {
        $this->instructor->withSchema(BirdData::class);

        expect(call_user_func($this->getSchema))
            ->toBeInstanceOf(SchemaAdapter::class)
            ->toArray()
            ->toEqual(JsonSchema::toArray(BirdData::class));
    });

    it('sets the internal schema property to the provided Data class', function () {
        $this->instructor->withSchema(BirdData::class);

        $reflection = new ReflectionClass($this->instructor);
        $property = $reflection->getProperty('schema');
        $property->setAccessible(true);

        expect($property->getValue($this->instructor))->toBe(BirdData::class);
    });
});
