<?php

use BasilLangevin\InstructorLaravel\Concerns\ConfiguresProvider;
use BasilLangevin\InstructorLaravel\Facades\Instructor;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Providers\OpenAI\OpenAI;

covers(ConfiguresProvider::class);

it('sets the provider from the config if not set', function () {
    $this->preventRequestFromSending();

    config(['instructor.provider' => Provider::OpenAI]);

    $instructor = Instructor::make();
    $instructor->withSchema(BirdData::class)->generate();

    expect(getPrismRequest($instructor)->provider())->toBeInstanceOf(OpenAI::class);
});

it('sets the model from the config if not set', function () {
    preventRequestFromSending();

    config(['instructor.model' => 'gpt-4o']);

    $instructor = Instructor::make();
    $instructor->withSchema(BirdData::class)->generate();

    expect(getPrismRequest($instructor)->model())->toBe('gpt-4o');
});

it('does not change the provider if one is set', function () {
    preventRequestFromSending();
    $instructor = Instructor::make();
    $instructor->using(Provider::OpenAI, 'gpt-4o');

    config(['instructor.provider' => Provider::DeepSeek]);
    config(['instructor.model' => 'deepseek-chat']);

    $instructor->withSchema(BirdData::class)->generate();

    $request = getPrismRequest($instructor);

    expect($request->provider())->toBeInstanceOf(OpenAI::class);
    expect($request->model())->toBe('gpt-4o');
});

it('throws an exception if no provider is set', function () {
    $this->expectException(Exception::class, 'A provider must be set to generate a response.');

    config(['instructor.provider' => null]);

    Instructor::make()->withSchema(BirdData::class)->generate();
});

it('throws an exception if no model is set', function () {
    $this->expectException(Exception::class, 'A model must be set to generate a response.');

    config(['instructor.model' => null]);

    Instructor::make()->withSchema(BirdData::class)->generate();
});

it('throws an exception if the provider config is not a Provider enum value', function () {
    $this->expectException(Exception::class, 'The config value of "instructor.provider" must be a Provider enum value.');

    config(['instructor.provider' => 'not-a-provider']);

    Instructor::make()->withSchema(BirdData::class)->generate();
});

it('throws an exception if the model config is not a string', function () {
    $this->expectException(Exception::class, 'The config value of "instructor.model" must be a string.');

    config(['instructor.model' => 123]);

    Instructor::make()->withSchema(BirdData::class)->generate();
});
