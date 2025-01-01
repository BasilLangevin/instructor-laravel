<?php

namespace BasilLangevin\Instructor\Concerns;

use Closure;
use Illuminate\Support\Facades\App;

trait ResolvesClosures
{
    /**
     * Return a value, resolving any closures.
     */
    protected function resolve($value)
    {
        return $value instanceof Closure ? App::call($value) : $value;
    }
}
