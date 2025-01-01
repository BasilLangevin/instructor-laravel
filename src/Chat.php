<?php

namespace BasilLangevin\Instructor;

use BasilLangevin\Instructor\Concerns\HasMessages;

class Chat
{
    use HasMessages;

    public function __construct() {}

    /**
     * Make a new chat instance.
     */
    public static function make(): self
    {
        return app(self::class);
    }
}
