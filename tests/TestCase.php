<?php

namespace BasilLangevin\InstructorLaravel\Tests;

use BasilLangevin\InstructorLaravel\InstructorLaravelServiceProvider;
use BasilLangevin\InstructorLaravel\Services\RetryService;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\LaravelDataJsonSchemas\LaravelDataJsonSchemasServiceProvider;
use Mockery;
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
        $config = require __DIR__.'/config/prism.php';
        $app['config']->set('prism', $config);
    }

    protected function preventRequestFromSending(): void
    {
        $this->mock(RetryService::class)->shouldReceive('retry')->once()
            ->with(4, Mockery::type('Closure'), 0, null)
            ->andReturn(BirdData::from(['species' => 'Chestnut-backed Chickadee']));
    }
}
