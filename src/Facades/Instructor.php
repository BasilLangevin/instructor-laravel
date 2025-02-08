<?php

namespace BasilLangevin\InstructorLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BasilLangevin\InstructorLaravel\Instructor
 */
class Instructor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BasilLangevin\InstructorLaravel\Instructor::class;
    }
}
