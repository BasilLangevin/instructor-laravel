<?php

namespace BasilLangevin\InstructorLaravel\Exceptions;

use Illuminate\Contracts\Validation\Validator;

class LaravelDataValidationException extends SchemaValidationException
{
    public function __construct(
        public Validator $validator,
    ) {
        $message = $validator->errors()->toJson(JSON_UNESCAPED_SLASHES);

        parent::__construct($message);
    }
}
