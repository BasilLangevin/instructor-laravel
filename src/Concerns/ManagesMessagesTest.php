<?php

use BasilLangevin\InstructorLaravel\Concerns\ManagesMessages;
use BasilLangevin\InstructorLaravel\Facades\Instructor;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;

covers(ManagesMessages::class);

it('can set the messages', function () {
    $fake = Instructor::fake(['species' => 'Pileated Woodpecker']);

    Instructor::withMessages([new UserMessage('What species is this bird?')])
        ->withSchema(BirdData::class)
        ->withoutRetries()
        ->generate();

    expect($fake->messages())->toHaveCount(1);
    expect($fake->messages()->first())
        ->toBeInstanceOf(UserMessage::class)
        ->text()->toBe('What species is this bird?');
});

test('withPrompt adds a user message', function () {
    $fake = Instructor::fake(['species' => 'Great Blue Heron']);

    Instructor::withPrompt('What species is this bird?')
        ->withSchema(BirdData::class)
        ->withoutRetries()
        ->generate();

    expect($fake->messages())->toHaveCount(1);
    expect($fake->messages()->first())
        ->toBeInstanceOf(UserMessage::class)
        ->text()->toBe('What species is this bird?');
});

it('can add a user message', function () {
    $fake = Instructor::fake(['species' => 'Marbled Murrelet']);

    Instructor::addUserMessage('What species is this bird?')
        ->withSchema(BirdData::class)
        ->withoutRetries()
        ->generate();

    expect($fake->messages())->toHaveCount(1);
    expect($fake->messages()->last())
        ->toBeInstanceOf(UserMessage::class)
        ->text()->toBe('What species is this bird?');
});

it('can add an assistant message', function () {
    $fake = Instructor::fake(['species' => 'Black Oystercatcher']);

    Instructor::addAssistantMessage('What species is this bird?')
        ->withSchema(BirdData::class)
        ->withoutRetries()
        ->generate();

    expect($fake->messages())->toHaveCount(1);
    expect($fake->messages()->last())
        ->toBeInstanceOf(AssistantMessage::class)
        ->content->toBe('What species is this bird?');
});

it('adds retry messages when a response is not valid', function () {
    $fake = Instructor::fake([
        ['weight_in_grams' => 132],
        ['species' => 'Pacific Wren'],
    ]);

    Instructor::addUserMessage('What species is this bird?')
        ->withSchema(BirdData::class)
        ->withRetries(1)
        ->generate();

    expect($fake->messages())->toHaveCount(1);

    expect($fake->messages(1))->toHaveCount(3);

    expect($fake->message(1, 0))->toBeInstanceOf(UserMessage::class)
        ->text()->toBe('What species is this bird?');
    expect($fake->message(1, 1))->toBeInstanceOf(AssistantMessage::class)
        ->content->toBe(json_encode(['weight_in_grams' => 132]));
    expect($fake->message(1, 2))->toBeInstanceOf(UserMessage::class)
        ->text()->toContain('{"/":["The required properties (species) are missing","Additional object properties are not allowed: weight_in_grams"]}');
});
