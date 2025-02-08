<?php

namespace BasilLangevin\InstructorLaravel\Tests;

use BasilLangevin\InstructorLaravel\InstructorLaravelServiceProvider;
use BasilLangevin\LaravelDataJsonSchemas\LaravelDataJsonSchemasServiceProvider;
use Dotenv\Dotenv;
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

    /**
     * Setup the environment to test against real APIs.
     */
    protected function getEnvironmentSetUp($app)
    {
        if (! file_exists(__DIR__.'/../.env.testing')) {
            return;
        }

        $dotenv = Dotenv::createImmutable(__DIR__, '/../.env.testing');
        $dotenv->load();

        $config = require __DIR__.'/config/prism.php';
        $app['config']->set('prism', $config);
    }
}
