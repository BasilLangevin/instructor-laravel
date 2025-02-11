<?php

namespace BasilLangevin\InstructorLaravel\Concerns;

use BasilLangevin\InstructorLaravel\Services\RetryService;
use EchoLabs\Prism\Structured\PendingRequest;

/**
 * @property PendingRequest $request
 */
trait RetriesRequest
{
    /** @var int|array<int, int> */
    protected $times = 3;

    /** @var int|\Closure(int, \Throwable): int */
    protected $sleepMilliseconds = 0;

    /** @var (callable(\Throwable): bool)|null */
    protected $when = null;

    protected RetryService $retryService;

    protected function initializeRetriesRequest(): void
    {
        $this->retryService = app(RetryService::class);
    }

    /**
     * @param  int|array<int, int>  $times
     * @param  int|\Closure(int, \Throwable): int  $sleepMilliseconds
     * @param  (callable(\Throwable): bool)|null  $when
     */
    public function withRetries($times = 3, $sleepMilliseconds = 0, ?callable $when = null): static
    {
        $this->times = $times;
        $this->sleepMilliseconds = $sleepMilliseconds;
        $this->when = $when;

        return $this;
    }

    public function withoutRetries(): static
    {
        $this->times = 0;

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
        $times = is_int($this->times)
            ? $this->times + 1
            : [$this->times[0], ...$this->times];

        return $this->retryService->retry($times, $callback, $this->sleepMilliseconds, $this->when);
    }
}
