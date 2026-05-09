<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planned_route_points', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_route_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('checkpoint_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('sequence_order')->nullable();

            $table->timestamps();

            $table->unique(['daily_route_id', 'checkpoint_id']);
            $table->index(['daily_route_id', 'sequence_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_route_points');
    }
};
