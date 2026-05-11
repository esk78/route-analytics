<?php

namespace App\Console\Commands;

use App\Services\CheckpointGenerator;
use Illuminate\Console\Command;

class GenerateCheckpoints extends Command
{
    protected $signature = 'checkpoints:generate
                            {count=1000000 : Number of checkpoints to generate}
                            {--chunk=5000 : Insert chunk size}
                            {--fresh : Delete existing checkpoints before generation}';

    protected $description = 'Generate a large amount of checkpoints';

    public function handle(CheckpointGenerator $generator): int
    {
        $count = (int) $this->argument('count');
        $chunkSize = (int) $this->option('chunk');
        $fresh = (bool) $this->option('fresh');

        $this->info("Generating {$count} checkpoints...");

        $generator->generate(
            count: $count,
            chunkSize: $chunkSize,
            fresh: $fresh
        );

        $this->info('Checkpoints generated successfully.');

        return self::SUCCESS;
    }
}
