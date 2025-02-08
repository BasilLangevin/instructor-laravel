<?php

use EchoLabs\Prism\Enums\Provider;

// config for BasilLangevin/InstructorLaravel
return [
    /*
    |--------------------------------------------------------------------------
    | Default LLM Provider
    |--------------------------------------------------------------------------
    |
    | This value is the default LLM provider that this package will use to
    | generate a structured response that it will transform into a Data
    | object. You may also set LLM providers on a per-request basis.
    */
    'provider' => Provider::OpenAI,

    /*
    |--------------------------------------------------------------------------
    | Default LLM Model
    |--------------------------------------------------------------------------
    |
    | This value is the default LLM model that this package will use
    | for the LLM provider when generating a structured response.
    | You may also set the model each time you make a request.
    */
    'model' => 'gpt-4o',
];
