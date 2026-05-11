<?php

namespace App\Services;

use App\Models\Checkpoint;
use Illuminate\Support\Facades\DB;

class CheckpointGenerator
{
    public function generate(
        int $count = 1_000_000,
        int $chunkSize = 5000,
        bool $fresh = false
    ): void {
        if ($fresh) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Checkpoint::query()->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $created = 0;

        while ($created < $count) {
            $currentChunkSize = min($chunkSize, $count - $created);

            $rows = [];

            for ($i = 0; $i < $currentChunkSize; $i++) {
                $number = $created + $i + 1;

                $rows[] = [
                    'name' => "Checkpoint $number",
                    'latitude' => fake()->randomFloat(7, 49.85, 48.08),
                    'longitude' => fake()->randomFloat(7, 27.38, 29.98),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('checkpoints')->insert($rows);

            $created += $currentChunkSize;
        }
    }
}
