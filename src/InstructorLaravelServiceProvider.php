<?php

namespace BasilLangevin\InstructorLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class InstructorLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('instructor-laravel')
            ->hasConfigFile('instructor');
    }
}
