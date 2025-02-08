<?php

use BasilLangevin\InstructorLaravel\Tests\Support\Facades\ResponseBuilder;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Schema\ObjectSchema;
use EchoLabs\Prism\Schema\StringSchema;

covers(\BasilLangevin\InstructorLaravel\Tests\Support\ResponseBuilder::class);

it('can fake a structured response', function () {
    ResponseBuilder::fake(['species' => 'Eagle']);

    $response = Prism::structured()
        ->using(Provider::DeepSeek, 'deepseek-chat')
        ->withSchema(new ObjectSchema(
            name: 'Bird',
            description: 'A bird',
            properties: [
                new StringSchema(name: 'species', description: 'The species of the bird'),
            ],
            requiredFields: ['species'],
        ))
        ->generate();

    expect($response->structured)->toEqual(['species' => 'Eagle']);
});

it('can set the provider', function () {
    $builder = ResponseBuilder::provider(Provider::OpenAI);

    expect($builder->getProvider())->toEqual(Provider::OpenAI);
});

it('throws an exception when an unsupported provider is used', function () {
    ResponseBuilder::provider(Provider::OpenAI)->fake(['species' => 'Eagle']);
})->throws(\Exception::class, 'Provider not supported by test response builder');
