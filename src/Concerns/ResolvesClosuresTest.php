<?php

use BasilLangevin\Instructor\Concerns\ResolvesClosures;

class ResolvesClosuresTester
{
    use ResolvesClosures;

    public static function call($value)
    {
        return (new self)->resolve($value);
    }
}

it('resolves closures', function () {
    expect(ResolvesClosuresTester::call(fn () => 'Hello, world!'))->toEqual('Hello, world!');
});

it('returns the value if it is not a closure', function ($value) {
    expect(ResolvesClosuresTester::call($value))->toEqual($value);
})->with([
    'Hello, world!',
    collect(['Hello, world!']),
]);
