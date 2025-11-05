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
        Schema::create('cranes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['light-truck', 'refrigrated', 'large-freight', 'tanker-truck']);
            $table->integer('capacity')->unsigned()->default(0);
            $table->string('license_plate')->nullable()->unique();
            $table->string('image')->nullable();
            $table->enum('status', ['available', 'in_progress'])->default('available');
            $table->boolean('is_online')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cranes');
    }
};
