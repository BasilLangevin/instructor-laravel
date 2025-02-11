<?php

namespace BasilLangevin\InstructorLaravel\Facades;

use BasilLangevin\InstructorLaravel\Tests\Support\InstructorFake;
use EchoLabs\Prism\ValueObjects\ProviderResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @phpstan-import-type ResponseObject from InstructorFake
 * @phpstan-import-type ResponseArray from InstructorFake
 *
 * @see \BasilLangevin\InstructorLaravel\Instructor
 */
class Instructor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BasilLangevin\InstructorLaravel\Instructor::class;
    }

    /**
     * @param  ResponseArray|ResponseObject  $responses
     */
    public static function fake(array|ProviderResponse $responses): InstructorFake
    {
        return new InstructorFake($responses);
    }
}
