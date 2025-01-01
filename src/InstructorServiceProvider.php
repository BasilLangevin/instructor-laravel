<?php

namespace BasilLangevin\Instructor;

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
