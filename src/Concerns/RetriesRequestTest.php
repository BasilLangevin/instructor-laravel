<?php

use BasilLangevin\InstructorLaravel\Concerns\RetriesRequest;
use BasilLangevin\InstructorLaravel\Facades\Instructor;
use BasilLangevin\InstructorLaravel\Services\RetryService;

covers(RetriesRequest::class);

it('can set the retry configuration', function () {
    $instructor = Instructor::make();

    $instructor->withRetries(3, 1000, fn () => null);

    $reflection = new ReflectionClass($instructor);
    $times = $reflection->getProperty('times');
    $times->setAccessible(true);

    expect($times->getValue($instructor))->toBe(3);

    $sleepMilliseconds = $reflection->getProperty('sleepMilliseconds');
    $sleepMilliseconds->setAccessible(true);

    expect($sleepMilliseconds->getValue($instructor))->toBe(1000);

    $when = $reflection->getProperty('when');
    $when->setAccessible(true);

    expect($when->getValue($instructor))->toBeInstanceOf(Closure::class);
});

it('can disable retries', function () {
    $instructor = Instructor::make();

    $instructor->withoutRetries();

    $reflection = new ReflectionClass($instructor);
    $times = $reflection->getProperty('times');
    $times->setAccessible(true);

    expect($times->getValue($instructor))->toBe(1);
});

it('can retry a callback', function () {
    $this->mock(RetryService::class)->shouldReceive('retry')->once()
        ->with(3, Mockery::type('Closure'), 0, null)
        ->andReturn('Hello, world!');

    $instructor = Instructor::make();

    $reflection = new ReflectionClass($instructor);
    $method = $reflection->getMethod('retry');
    $method->setAccessible(true);

    $result = $method->invoke($instructor, fn () => 'Hello, world!');

    expect($result)->toBe('Hello, world!');
});

test('retry passes the correct arguments to the retry service', function () {
    $this->mock(RetryService::class)->shouldReceive('retry')->once()
        ->with(12, Mockery::type('Closure'), 800, Mockery::type('Closure'))
        ->andReturn('Hello, world!');

    $instructor = Instructor::make();

    $instructor->withRetries(12, 800, fn () => true);

    $reflection = new ReflectionClass($instructor);
    $method = $reflection->getMethod('retry');
    $method->setAccessible(true);

    $result = $method->invoke($instructor, fn () => 'Hello, world!');

    expect($result)->toBe('Hello, world!');
});
