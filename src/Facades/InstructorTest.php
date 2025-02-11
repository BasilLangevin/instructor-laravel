<?php

use BasilLangevin\InstructorLaravel\Facades\Instructor as InstructorFacade;
use BasilLangevin\InstructorLaravel\Instructor;

covers(InstructorFacade::class);

it('is a facade for the Instructor class', function () {
    $instructor = InstructorFacade::make();

    expect($instructor)->toBeInstanceOf(Instructor::class);
});
