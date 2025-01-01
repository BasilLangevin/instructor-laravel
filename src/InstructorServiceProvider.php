<?php

namespace BasilLangevin\Instructor;

use BasilLangevin\Instructor\Commands\InstructorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class InstructorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('instructor-laravel')
            ->hasConfigFile('instructor');
    }
}
