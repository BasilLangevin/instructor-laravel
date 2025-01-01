<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Chat Completion Service
    |--------------------------------------------------------------------------
    |
    | This value is the name of the chat completion API which will
    | be used by default when you create an Instructor request.
    | You may use any value defined in the 'services' array.
    |
    */
    'default_service' => env('INSTRUCTOR_SERVICE', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | LLM Chat Completion Services
    |--------------------------------------------------------------------------
    |
    | This value contains configuration settings for various LLM chat
    | completion services such as OpenAI, Anthropic, DeepSeek, and
    | more. To add additional services, follow existing format.
    |
    */

    'services' => [
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'api_url' => 'https://api.anthropic.com/v1',
            'endpoint' => '/messages',
            'model' => 'claude-3-5-sonnet-20241022',
            'max_tokens' => 1024,
        ],
        'deepseek' => [
            'api_key' => env('DEEPSEEK_API_KEY'),
            'api_url' => 'https://api.deepseek.com/v1',
            'endpoint' => '/chat/completions',
            'model' => 'deepseek-chat',
            'max_tokens' => 1024,
        ],
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'api_url' => 'https://api.openai.com/v1',
            'endpoint' => '/chat/completions',
            'model' => 'gpt-4o-mini',
            'metadata' => [
                'organization' => '',
                'project' => '',
            ],
            'max_tokens' => 1024,
        ],
    ],
];
