<?php

use BasilLangevin\InstructorLaravel\Facades\Instructor as InstructorFacade;
use BasilLangevin\InstructorLaravel\Instructor;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\InstructorLaravel\Tests\Support\InstructorFake;

covers(InstructorFacade::class);

it('is a facade for the Instructor class', function () {
    $instructor = InstructorFacade::make();

    expect($instructor)->toBeInstanceOf(Instructor::class);
});

it('can be faked', function () {
    $fake = InstructorFacade::fake(['species' => 'Western Tanager']);

    $instructor = InstructorFacade::make()->withSchema(BirdData::class);

    $result = $instructor->generate();

    expect($result)->toBeInstanceOf(BirdData::class);
    expect($result->species)->toBe('Western Tanager');

    expect($fake)->toBeInstanceOf(InstructorFake::class);
    expect($fake->requests())->toHaveCount(1);
});
