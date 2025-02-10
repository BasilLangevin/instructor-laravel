<?php

use BasilLangevin\InstructorLaravel\Exceptions\LaravelDataValidationException;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use Illuminate\Validation\ValidationException;

covers(LaravelDataValidationException::class);

it('sets its message to the formatted error', function () {
    try {
        BirdData::validate(['weight_in_grams' => 457]);
    } catch (ValidationException $e) {
        $exception = new LaravelDataValidationException($e->validator);

        expect($exception->getMessage())->toBe('{"species":["The species field is required."]}');
    }
});
