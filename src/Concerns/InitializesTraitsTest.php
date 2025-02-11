<?php

namespace BasilLangevin\InstructorLaravel\Tests\Concerns;

use BasilLangevin\InstructorLaravel\Concerns\InitializesTraits;

// Test Traits
trait TestTraitOne
{
    public bool $traitOneInitialized = false;

    public function initializeTestTraitOne(): void
    {
        $this->traitOneInitialized = true;
    }
}

trait TestTraitTwo
{
    public bool $traitTwoInitialized = false;

    public function initializeTestTraitTwo(): void
    {
        $this->traitTwoInitialized = true;
    }
}

trait TestTraitWithoutInitializer
{
    public string $value = 'default';
}

class TestClass
{
    use InitializesTraits;
    use TestTraitOne;
    use TestTraitTwo;
    use TestTraitWithoutInitializer;

    public function __construct()
    {
        $this->initializeTraits();
    }
}

it('initializes all traits with initialize methods')
    ->expect(fn () => new TestClass)
    ->traitOneInitialized->toBeTrue()
    ->traitTwoInitialized->toBeTrue();

it('safely skips traits without initialize methods')
    ->expect(fn () => (new TestClass)->value)
    ->toBe('default');

it('handles classes with no traits gracefully', function () {
    $classWithNoTraits = new class
    {
        use InitializesTraits;

        public function __construct()
        {
            $this->initializeTraits();
        }
    };

    expect($classWithNoTraits)->toBeObject();
});
