<?php

use BasilLangevin\InstructorLaravel\Facades\Instructor as InstructorFacade;
use BasilLangevin\InstructorLaravel\Instructor;

covers(Instructor::class);

beforeEach(function () {
    $this->instructor = InstructorFacade::make();

    $reflection = new ReflectionClass($this->instructor);
    $property = $reflection->getProperty('request');
    $property->setAccessible(true);

    $this->request = $property->getValue($this->instructor);
});

it('can be created via its facade')
    ->expect(fn () => InstructorFacade::make())
    ->toBeInstanceOf(Instructor::class);

it('can pass method calls to the underlying Prism instance', function () {
    $this->instructor->withPrompt('Hello, world!');

    $reflection = new ReflectionClass($this->request);
    $property = $reflection->getProperty('prompt');
    $property->setAccessible(true);

    expect($property->getValue($this->request))->toBe('Hello, world!');
});
