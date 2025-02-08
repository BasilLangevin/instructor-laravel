<?php

namespace BasilLangevin\InstructorLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BasilLangevin\InstructorLaravel\InstructorLaravel
 */
class InstructorLaravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BasilLangevin\InstructorLaravel\InstructorLaravel::class;
    }
}
