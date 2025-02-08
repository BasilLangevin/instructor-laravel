<?php

namespace BasilLangevin\InstructorLaravel;

use BasilLangevin\InstructorLaravel\Commands\InstructorLaravelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class InstructorLaravelServiceProvider extends PackageServiceProvider
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
            ->hasConfigFile('instructor')
            ->hasViews()
            ->hasMigration('create_instructor_laravel_table')
            ->hasCommand(InstructorLaravelCommand::class);
    }
}
