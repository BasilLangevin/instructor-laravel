<?php

namespace BasilLangevin\Instructor\Commands;

use Illuminate\Console\Command;

class InstructorCommand extends Command
{
    public $signature = 'instructor-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}