<?php

use BasilLangevin\InstructorLaravel\Collections\MessageThread;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;

covers(MessageThread::class);

beforeEach(function () {
    $this->collection = MessageThread::make([new UserMessage('First message')]);
});

it('can add a user message', function () {
    $this->collection->addUserMessage('Hello, world!');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(UserMessage::class);
});

it('can add a user message with the user method', function () {
    $this->collection->user('Hello, world!');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(UserMessage::class);
});

it('can add an assistant message', function () {
    $this->collection->addAssistantMessage('Hello, world!');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(AssistantMessage::class);
});

it('can add an assistant message with the assistant method', function () {
    $this->collection->assistant('Hello, world!');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(AssistantMessage::class);
});
