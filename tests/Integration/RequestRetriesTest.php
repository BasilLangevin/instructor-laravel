<?php

use BasilLangevin\InstructorLaravel\Facades\Instructor;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;

it('retries a request when the response is invalid', function () {
    $fake = Instructor::fake(['species' => 'Western Tanager']);

    $instructor = Instructor::make()->withSchema(BirdData::class)
        ->addUserMessage('What is the species of the bird?');

    $result = $instructor->generate();

    expect($result)->toBeInstanceOf(BirdData::class);
    expect($result->species)->toBe('Western Tanager');

    expect($fake->requests())->toHaveCount(1);
    expect($fake->message())->toBeInstanceOf(UserMessage::class)
        ->text()->toBe('What is the species of the bird?');
});
