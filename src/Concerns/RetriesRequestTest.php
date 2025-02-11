<?php

use BasilLangevin\InstructorLaravel\Concerns\RetriesRequest;
use BasilLangevin\InstructorLaravel\Facades\Instructor;
use BasilLangevin\InstructorLaravel\Services\RetryService;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;

covers(RetriesRequest::class);

it('sets the retry configuration and adds one retry to the times integer parameter', function () {
    $this->mock(RetryService::class)->shouldReceive('retry')->once()
        ->with(12, Mockery::type('Closure'), 800, Mockery::type('Closure'))
        ->andReturn(BirdData::from(['species' => 'Belted Kingfisher']));

    Instructor::make()
        ->withSchema(BirdData::class)
        ->withRetries(11, 800, fn () => true)
        ->generate();
});

it('sets the retry configuration and adds an initial delay to an array of retry sleeps', function () {
    $this->mock(RetryService::class)->shouldReceive('retry')->once()
        ->with([100, 100, 200], Mockery::type('Closure'), 0, null)
        ->andReturn(BirdData::from(['species' => 'Downy Woodpecker']));

    Instructor::make()
        ->withSchema(BirdData::class)
        ->withRetries([100, 200])
        ->generate();
});

it('can disable retries', function () {
    $this->mock(RetryService::class)->shouldReceive('retry')->once()
        ->with(1, Mockery::type('Closure'), 0, null)
        ->andReturn(BirdData::from(['species' => 'Anna\'s Hummingbird']));

    Instructor::make()
        ->withSchema(BirdData::class)
        ->withoutRetries()
        ->generate();
});
