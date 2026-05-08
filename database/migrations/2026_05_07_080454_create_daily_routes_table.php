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
        Schema::create('daily_routes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspector_id')->constrained()->cascadeOnDelete();

            $table->date('route_date');

            $table->unsignedInteger('planned_points_count')->default(0);
            $table->unsignedInteger('completed_points_count')->default(0);

            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->decimal('average_speed', 8, 2)->nullable();

            $table->timestamps();

            $table->unique(['inspector_id', 'route_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_routes');
    }
};
