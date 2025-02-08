<?php

namespace BasilLangevin\InstructorLaravel\Tests\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ResponseBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BasilLangevin\InstructorLaravel\Tests\Support\ResponseBuilder::class;
    }
}
