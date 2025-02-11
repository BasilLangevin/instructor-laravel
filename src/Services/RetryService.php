<?php

namespace BasilLangevin\InstructorLaravel\Services;

/**
 * A service that retries an operation a given number of times.
 *
 * This service is used to make testing easier.
 */
class RetryService
{
    /**
     * Retry an operation a given number of times.
     *
     * @template TValue
     *
     * @param  int|array<int, int>  $times
     * @param  callable(int): TValue  $callback
     * @param  int|\Closure(int, \Throwable): int  $sleepMilliseconds
     * @param  (callable(\Throwable): bool)|null  $when
     * @return TValue
     *
     * @throws \Throwable
     */
    public function retry($times, callable $callback, $sleepMilliseconds = 0, $when = null)
    {
        return retry($times, $callback, $sleepMilliseconds, $when);
    }
}
