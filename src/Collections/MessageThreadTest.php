<?php

use BasilLangevin\InstructorLaravel\Collections\MessageThread;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;

covers(MessageThread::class);

beforeEach(function () {
    $this->collection = MessageThread::make([new UserMessage('First message')]);
});

it('can add a user message', function () {
    $this->collection->addUserMessage('What species is this bird?');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(UserMessage::class);
});

it('can add a user message with the user method', function () {
    $this->collection->user('What species is this bird?');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(UserMessage::class);
});

it('can add an assistant message', function () {
    $this->collection->addAssistantMessage('What species is this bird?');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(AssistantMessage::class);
});

it('can add an assistant message with the assistant method', function () {
    $this->collection->assistant('What species is this bird?');

    expect($this->collection)
        ->count()->toBe(2)
        ->last()->toBeInstanceOf(AssistantMessage::class);
});
