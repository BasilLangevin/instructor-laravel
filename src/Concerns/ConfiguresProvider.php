<?php

namespace BasilLangevin\InstructorLaravel\Concerns;

use EchoLabs\Prism\Enums\Provider;

trait ConfiguresProvider
{
    /**
     * Whether a provider has been manually set.
     */
    protected bool $hasProvider = false;

    /**
     * Set the LLM provider and model for the request.
     */
    public function using(string|Provider $provider, string $model): self
    {
        $this->hasProvider = true;

        $this->request->using($provider, $model);

        return $this;
    }

    /**
     * Set the provider and model to their config values if not already set.
     */
    protected function ensureProviderIsSet(): void
    {
        if ($this->hasProvider) {
            return;
        }

        if (! $provider = config('instructor.provider')) {
            throw new \Exception('A provider must be set to generate a response.');
        }

        if (! in_array($provider, Provider::cases())) {
            throw new \Exception('The config value of "instructor.provider" must be a Provider enum value.');
        }

        if (! $model = config('instructor.model')) {
            throw new \Exception('A model must be set to generate a response.');
        }

        if (! is_string($model)) {
            throw new \Exception('The config value of "instructor.model" must be a string.');
        }

        $this->using($provider, $model);
    }
}
