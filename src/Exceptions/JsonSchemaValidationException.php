<?php

namespace BasilLangevin\InstructorLaravel\Exceptions;

use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\ValidationResult;

class JsonSchemaValidationException extends SchemaValidationException
{
    public function __construct(
        public ValidationResult $result,
    ) {
        $formatter = app(ErrorFormatter::class);

        /** @var ValidationError $error */
        $error = $result->error();

        $formattedError = $formatter->format($error);

        /** @var non-empty-string $message */
        $message = json_encode($formattedError, JSON_UNESCAPED_SLASHES);

        parent::__construct($message);
    }
}
