<?php

use BasilLangevin\InstructorLaravel\Concerns\ValidatesResponse;
use BasilLangevin\InstructorLaravel\Exceptions\JsonSchemaValidationException;
use BasilLangevin\InstructorLaravel\Exceptions\LaravelDataValidationException;
use BasilLangevin\InstructorLaravel\Facades\Instructor as InstructorFacade;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\InstructorLaravel\Tests\Support\Facades\ResponseBuilder;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

covers(ValidatesResponse::class);

beforeEach(function () {
    $this->instructor = InstructorFacade::make();

    $reflection = new ReflectionClass($this->instructor);
    $property = $reflection->getProperty('request');
    $property->setAccessible(true);

    $this->request = $property->getValue($this->instructor);
});

it('can validate a response that does not match the schema', function () {
    ResponseBuilder::fake(['weight_in_grams' => 457]);

    $this->instructor->withSchema(BirdData::class)
        ->withoutRetries()
        ->generate();
})->throws(JsonSchemaValidationException::class);

it('can validate a response that does not match the Laravel Data attributes', function () {
    /** We use the #[Rule] attribute because it can't be validated by the JsonSchema validator. */
    class LaravelValidatedAttributeData extends Data
    {
        public function __construct(
            #[Rule('max:100')]
            public int $age,
        ) {}
    }

    ResponseBuilder::fake(['age' => 101]);

    $this->instructor->withSchema(LaravelValidatedAttributeData::class)
        ->withoutRetries()
        ->generate();
})->throws(LaravelDataValidationException::class);
