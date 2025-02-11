<?php

use BasilLangevin\InstructorLaravel\Services\RetryService;

covers(RetryService::class);

it('retries the callback', function () {
    $service = new RetryService;

    $result = $service->retry(3, fn () => 'success');

    expect($result)->toBe('success');
});
