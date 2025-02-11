<?php

namespace BasilLangevin\InstructorLaravel\Concerns;

trait InitializesTraits
{
    public function initializeTraits(): void
    {
        /** @var array<string, string> */
        $traits = class_uses_recursive(static::class);

        collect($traits)
            ->map(fn (string $trait) => 'initialize'.class_basename($trait))
            ->filter(fn (string $method) => method_exists(static::class, $method))
            ->each(fn (string $method) => $this->{$method}());
    }
}
