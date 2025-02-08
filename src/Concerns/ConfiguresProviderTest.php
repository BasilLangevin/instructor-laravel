<?php

use BasilLangevin\InstructorLaravel\Concerns\ConfiguresProvider;
use BasilLangevin\InstructorLaravel\Facades\Instructor as InstructorFacade;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\InstructorLaravel\Tests\Support\Facades\ResponseBuilder;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Providers\OpenAI\OpenAI;

covers(ConfiguresProvider::class);

beforeEach(function () {
    ResponseBuilder::fake(['species' => 'Harlequin Duck']);

    $this->instructor = InstructorFacade::make();

    $reflection = new ReflectionClass($this->instructor);
    $property = $reflection->getProperty('request');
    $property->setAccessible(true);

    $this->request = $property->getValue($this->instructor);
});

it('sets the provider from the config if not set', function () {
    config(['instructor.provider' => Provider::OpenAI]);

    $this->instructor->withSchema(BirdData::class)->generate();

    expect($this->request->provider())->toBeInstanceOf(OpenAI::class);
});

it('sets the model from the config if not set', function () {
    config(['instructor.model' => 'gpt-4o']);

    $this->instructor->withSchema(BirdData::class)->generate();

    expect($this->request->model())->toBe('gpt-4o');
});

it('does not change the provider if one is set', function () {
    $this->instructor->using(Provider::OpenAI, 'gpt-4o');

    config(['instructor.provider' => Provider::DeepSeek]);
    config(['instructor.model' => 'deepseek-chat']);

    $this->instructor->withSchema(BirdData::class)->generate();

    expect($this->request->provider())->toBeInstanceOf(OpenAI::class);
    expect($this->request->model())->toBe('gpt-4o');
});

it('throws an exception if no provider is set', function () {
    $this->expectException(Exception::class, 'A provider must be set to generate a response.');

    config(['instructor.provider' => null]);

    $this->instructor->withSchema(BirdData::class)->generate();
});

it('throws an exception if no model is set', function () {
    $this->expectException(Exception::class, 'A model must be set to generate a response.');

    config(['instructor.model' => null]);

    $this->instructor->withSchema(BirdData::class)->generate();
});

it('throws an exception if the provider config is not a Provider enum value', function () {
    $this->expectException(Exception::class, 'The config value of "instructor.provider" must be a Provider enum value.');

    config(['instructor.provider' => 'not-a-provider']);

    $this->instructor->withSchema(BirdData::class)->generate();
});

it('throws an exception if the model config is not a string', function () {
    $this->expectException(Exception::class, 'The config value of "instructor.model" must be a string.');

    config(['instructor.model' => 123]);

    $this->instructor->withSchema(BirdData::class)->generate();
});
