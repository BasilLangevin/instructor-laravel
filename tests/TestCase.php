<?php

namespace BasilLangevin\Instructor\Tests;

use BasilLangevin\Instructor\InstructorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            InstructorServiceProvider::class,
        ];
    }
}
