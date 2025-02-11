<?php

namespace BasilLangevin\InstructorLaravel;

use Opis\JsonSchema\Validator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class InstructorLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('instructor-laravel')
            ->hasConfigFile('instructor')
            ->hasTranslations();
    }

    public function registeringPackage(): void
    {
        $this->app->bind(Validator::class, fn () => (new Validator)
            ->setMaxErrors(10)
            ->setStopAtFirstError(false));
    }
}
