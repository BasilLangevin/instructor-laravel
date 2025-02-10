<?php

namespace BasilLangevin\InstructorLaravel\Concerns;

use BasilLangevin\InstructorLaravel\Exceptions\JsonSchemaValidationException;
use BasilLangevin\InstructorLaravel\Exceptions\LaravelDataValidationException;
use BasilLangevin\InstructorLaravel\Exceptions\SchemaValidationException;
use EchoLabs\Prism\Structured\Response;
use Illuminate\Validation\ValidationException;
use Opis\JsonSchema\Helper;
use Opis\JsonSchema\Validator;

trait ValidatesResponse
{
    /**
     * Validate the structured response from the provider.
     *
     * @throws SchemaValidationException
     */
    protected function validateResponse(Response $response): void
    {
        /** @var array<string, mixed> $data */
        $data = $response->structured;

        $this->validateJsonSchema($data);
        $this->validateDataAttributes($data);
    }

    /**
     * Validate the JSON schema of the response.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws SchemaValidationException
     */
    protected function validateJsonSchema(array $data): void
    {
        /** @var object $data */
        $data = Helper::toJson($data);

        /** @var object $schema */
        $schema = Helper::toJson($this->adapter->toArray());

        $validator = app(Validator::class);
        $result = $validator->validate($data, $schema);

        if ($result->hasError()) {
            throw new JsonSchemaValidationException($result);
        }

    }

    /**
     * Validate the data attributes of the response.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws SchemaValidationException
     */
    protected function validateDataAttributes(array $data): void
    {
        try {
            $this->schema::validate($data);
        } catch (ValidationException $e) {
            throw new LaravelDataValidationException($e->validator);
        }
    }
}
