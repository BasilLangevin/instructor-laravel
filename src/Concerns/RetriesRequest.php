<?php

namespace BasilLangevin\InstructorLaravel\Concerns;

trait RetriesRequest
{
    /** @var int|array<int, int> */
    protected $times = 3;

    /** @var int|\Closure(int, \Throwable): int */
    protected $sleepMilliseconds = 0;

    /** @var (callable(\Throwable): bool)|null */
    protected $when = null;

    /**
     * @param  int|array<int, int>  $times
     * @param  int|\Closure(int, \Throwable): int  $sleepMilliseconds
     * @param  (callable(\Throwable): bool)|null  $when
     */
    public function withRetry($times = 3, $sleepMilliseconds = 0, ?callable $when = null): static
    {
        $this->times = $times;
        $this->sleepMilliseconds = $sleepMilliseconds;
        $this->when = $when;

        return $this;
    }

    /**
     * @template TValue
     *
     * @param  callable(int): TValue  $callback
     * @return TValue
     */
    protected function retry(callable $callback)
    {
        return retry($this->times, $callback, $this->sleepMilliseconds, $this->when);
    }
}
