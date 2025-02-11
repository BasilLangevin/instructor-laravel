<?php

use BasilLangevin\InstructorLaravel\Concerns\ValidatesResponse;
use BasilLangevin\InstructorLaravel\Exceptions\JsonSchemaValidationException;
use BasilLangevin\InstructorLaravel\Exceptions\LaravelDataValidationException;
use BasilLangevin\InstructorLaravel\Facades\Instructor;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

covers(ValidatesResponse::class);

it('can validate a response that does not match the schema', function () {
    Instructor::fake(['weight_in_grams' => 457]);

    Instructor::make()
        ->withSchema(BirdData::class)
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

    Instructor::fake(['age' => 101]);

    Instructor::make()
        ->withSchema(LaravelValidatedAttributeData::class)
        ->withoutRetries()
        ->generate();
})->throws(LaravelDataValidationException::class);
