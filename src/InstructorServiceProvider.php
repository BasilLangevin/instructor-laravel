<?php

namespace BasilLangevin\Instructor;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use BasilLangevin\Instructor\Commands\InstructorCommand;

class InstructorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('instructor-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_instructor_laravel_table')
            ->hasCommand(InstructorCommand::class);
    }
}