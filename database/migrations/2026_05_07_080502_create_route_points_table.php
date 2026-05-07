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
        Schema::create('route_points', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_route_id')->constrained()->cascadeOnDelete();

            $table->foreignId('checkpoint_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->timestamp('visited_at');

            $table->unsignedInteger('sequence_order')->nullable();

            $table->boolean('is_planned')->default(true);
            $table->boolean('is_visited')->default(false);

            $table->decimal('speed_from_previous', 8, 2)->nullable();

            $table->timestamps();

            $table->index(['daily_route_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_points');
    }
};
