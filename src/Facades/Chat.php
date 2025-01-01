<?php

namespace BasilLangevin\Instructor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BasilLangevin\Instructor\Chat
 */
class Chat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BasilLangevin\Instructor\Chat::class;
    }
}
