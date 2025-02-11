<?php

use BasilLangevin\InstructorLaravel\Collections\MessageThread;
use BasilLangevin\InstructorLaravel\Concerns\ManagesMessages;
use BasilLangevin\InstructorLaravel\Exceptions\SchemaValidationException;
use BasilLangevin\InstructorLaravel\Facades\Instructor;
use EchoLabs\Prism\Enums\FinishReason;
use EchoLabs\Prism\Structured\Response;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use EchoLabs\Prism\ValueObjects\ResponseMeta;
use EchoLabs\Prism\ValueObjects\Usage;

covers(ManagesMessages::class);

beforeEach(function () {
    $this->instructor = Instructor::make();

    $this->reflection = new ReflectionClass($this->instructor);
    $property = $this->reflection->getProperty('messages');
    $property->setAccessible(true);

    $this->getMessages = fn () => $property->getValue($this->instructor);

    $requestProperty = $this->reflection->getProperty('request');
    $requestProperty->setAccessible(true);

    $this->getRequestMessages = function () use ($requestProperty) {
        $request = $requestProperty->getValue($this->instructor);

        $reflection = new ReflectionClass($request);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);

        return $property->getValue($request);
    };
});

it('initializes a message collection', function () {
    expect(call_user_func($this->getMessages))->toBeInstanceOf(MessageThread::class);
});

it('can set the messages', function () {
    $this->instructor->withMessages([new UserMessage('Hello, world!')]);

    $messages = call_user_func($this->getMessages);

    expect($messages)->toHaveCount(1);
    expect($messages->first())->toBeInstanceOf(UserMessage::class);

    expect(call_user_func($this->getRequestMessages))->toEqual($messages->all());
});

test('withPrompt adds a user message', function () {
    $this->instructor->withPrompt('Hello, world!');

    $messages = call_user_func($this->getMessages);

    expect($messages)->toHaveCount(1);
    expect($messages->first())->toBeInstanceOf(UserMessage::class);

    expect(call_user_func($this->getRequestMessages))->toEqual($messages->all());
});

it('can add a user message', function () {
    $this->instructor->addUserMessage('Hello, world!');

    $messages = call_user_func($this->getMessages);

    expect($messages)->toHaveCount(1);
    expect($messages->last())->toBeInstanceOf(UserMessage::class);

    expect(call_user_func($this->getRequestMessages))->toEqual($messages->all());
});

it('can add an assistant message', function () {
    $this->instructor->addAssistantMessage('Hello, world!');

    $messages = call_user_func($this->getMessages);

    expect($messages)->toHaveCount(1);
    expect($messages->last())->toBeInstanceOf(AssistantMessage::class);

    expect(call_user_func($this->getRequestMessages))->toEqual($messages->all());
});

it('adds a retry message when a schema validation exception occurs', function () {
    $method = $this->reflection->getMethod('addRetryMessages');
    $method->setAccessible(true);

    $response = new Response(
        collect(),
        collect(),
        '{"foo": "bar"}',
        null,
        FinishReason::Stop,
        new Usage(0, 0, 0),
        new ResponseMeta('', '')
    );

    $method->invoke($this->instructor, $response, new SchemaValidationException('Hello, world!'));

    $messages = call_user_func($this->getMessages);

    expect($messages)->toHaveCount(2);

    expect($messages->first())->toBeInstanceOf(AssistantMessage::class);
    expect($messages->first()->content)->toBe('{"foo": "bar"}');

    expect($messages->last())->toBeInstanceOf(UserMessage::class);
    expect($messages->last()->text())->toContain('Hello, world!');
});
