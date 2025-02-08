<?php

namespace BasilLangevin\InstructorLaravel\Tests;

use BasilLangevin\InstructorLaravel\InstructorLaravelServiceProvider;
use BasilLangevin\LaravelDataJsonSchemas\LaravelDataJsonSchemasServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            InstructorLaravelServiceProvider::class,
            LaravelDataJsonSchemasServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
}
