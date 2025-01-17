<?php

use BasilLangevin\Instructor\InstructorServiceProvider;
use Illuminate\Support\Facades\File;

it('publishes the config file', function () {
    $this->assertFileDoesNotExist(config_path('instructor.php'));

    $this->artisan('vendor:publish', ['--provider' => InstructorServiceProvider::class])->assertSuccessful();

    $this->assertFileExists(config_path('instructor.php'));

    File::delete(config_path('instructor.php'));
});
