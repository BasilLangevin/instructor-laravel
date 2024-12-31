<?php

namespace BasilLangevin\Instructor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BasilLangevin\Instructor\Instructor
 */
class Instructor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BasilLangevin\Instructor\Instructor::class;
    }
}
