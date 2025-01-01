<?php

use Exception;
use BasilLangevin\Instructor\Enums\Role;
use BasilLangevin\Instructor\Facades\Chat;

it('can add a system message string to a chat', function () {
    $chat = Chat::system('You are a helpful assistant.');

    expect($chat->resolveSystemMessage())->toBe('You are a helpful assistant.');
});

it('can add a system message callback to a chat', function () {
    $chat = Chat::system(fn() => 'You are a helpful assistant.');

    expect($chat->resolveSystemMessage())->toBe('You are a helpful assistant.');
});

it('can add a message string to a chat', function () {
    $chat = Chat::message('Test user message.');

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'Test user message.',
        ],
    ]));
});

it('can add a message callback to a chat', function () {
    $chat = Chat::message(fn() => 'Test user message.');

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'Test user message.',
        ],
    ]));
});

it('can add both string and callback messages to a chat', function () {
    $chat = Chat::message('First test message.')
        ->message(fn() => 'Second test message.');

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'First test message.',
        ],
        [
            'role' => Role::User,
            'content' => 'Second test message.',
        ],
    ]));
});

it('can add a user message to a chat', function () {
    $chat = Chat::user('Test user message.');

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'Test user message.',
        ],
    ]));
});

it('can add an assistant message to a chat', function () {
    $chat = Chat::assistant('Test assistant message.');

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::Assistant,
            'content' => 'Test assistant message.',
        ],
    ]));
});

it('can add subsequent messages to a chat', function () {
    $chat = Chat::user('First user message.')
        ->assistant('First assistant message.')
        ->user('Second user message.')
        ->assistant('Second assistant message.');

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'First user message.',
        ],
        [
            'role' => Role::Assistant,
            'content' => 'First assistant message.',
        ],
        [
            'role' => Role::User,
            'content' => 'Second user message.',
        ],
        [
            'role' => Role::Assistant,
            'content' => 'Second assistant message.',
        ],
    ]));
});

it('can add multiple messages with a single method call', function () {
    $chat = Chat::messages([
        ['role' => 'user', 'content' => 'First user message.'],
        ['role' => 'assistant', 'content' => 'First assistant message.'],
    ]);

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'First user message.',
        ],
        [
            'role' => Role::Assistant,
            'content' => 'First assistant message.',
        ],
    ]));
});

it('can add multiple messages with functions with a single method call', function () {
    $chat = Chat::messages([
        ['role' => 'user', 'content' => fn() => 'First user message.'],
        ['role' => 'assistant', 'content' => fn() => 'First assistant message.'],
    ]);

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'First user message.',
        ],
        [
            'role' => Role::Assistant,
            'content' => 'First assistant message.',
        ],
    ]));
});

it('can set a closure as the messages array', function () {
    $chat = Chat::messages(fn() => [
        ['role' => 'user', 'content' => 'First user message.'],
        ['role' => 'assistant', 'content' => 'First assistant message.'],
    ]);

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'First user message.',
        ],
        [
            'role' => Role::Assistant,
            'content' => 'First assistant message.',
        ],
    ]));
});

it('throws an exception if a message is added after the messages array is set with a closure', function () {
    Chat::messages(fn() => [
        ['role' => 'user', 'content' => 'First user message.'],
    ])->message('Second user message.');
})->throws(Exception::class, 'Individual messages cannot be added when the messages are set as a closure.');

test('prompt is an alias for user', function () {
    $chat = Chat::prompt('Test user message.');

    expect($chat->resolveMessages())->toEqual(collect([
        [
            'role' => Role::User,
            'content' => 'Test user message.',
        ],
    ]));
});
