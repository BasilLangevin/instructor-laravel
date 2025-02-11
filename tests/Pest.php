<?php

use BasilLangevin\InstructorLaravel\Instructor;
use BasilLangevin\InstructorLaravel\Services\RetryService;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\InstructorLaravel\Tests\TestCase;
use EchoLabs\Prism\Structured\PendingRequest;
use Mockery;

pest()->extend(TestCase::class)->in(__DIR__, __DIR__.'/../src');

function preventRequestFromSending(): void
{
    test()->mock(RetryService::class)->shouldReceive('retry')->once()
        ->with(4, Mockery::type('Closure'), 0, null)
        ->andReturn(BirdData::from(['species' => 'Chestnut-backed Chickadee']));
}

function getPrismRequest(Instructor $instructor): PendingRequest
{
    $reflection = new ReflectionClass($instructor);

    $property = $reflection->getProperty('request');
    $property->setAccessible(true);

    return $property->getValue($instructor);
}
