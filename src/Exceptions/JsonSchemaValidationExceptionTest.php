<?php

use BasilLangevin\InstructorLaravel\Exceptions\JsonSchemaValidationException;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use Opis\JsonSchema\Helper;
use Opis\JsonSchema\Validator;

covers(JsonSchemaValidationException::class);

it('sets its message to the formatted error', function () {
    $data = Helper::toJson([
        'weight_in_grams' => 457,
    ]);

    $schema = Helper::toJson(JsonSchema::toArray(BirdData::class));

    $validator = app(Validator::class);
    $error = $validator->validate($data, $schema);

    $exception = new JsonSchemaValidationException($error);

    expect($exception->getMessage())->toBe('{"/":["The required properties (species) are missing","Additional object properties are not allowed: weight_in_grams"]}');
});
