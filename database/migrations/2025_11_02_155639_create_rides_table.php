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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('crane_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('start_latitude',8,4)->nullable();
            $table->decimal('start_longitude',8,4)->nullable();
            $table->decimal('end_longitude',8,4)->nullable();
            $table->decimal('end_latitude',8,4)->nullable();
            $table->decimal('distance', 8, 2)->nullable()->unsigned();
            $table->integer('duration')->nullable()->unsigned();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['pending','accepted','refused', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->decimal('fare', 8, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
