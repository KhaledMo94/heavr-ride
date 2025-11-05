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
        Schema::table('cranes', function (Blueprint $table) {
            $table->integer('ratings_count')->unsigned()->default(0);
            $table->float('avg_rating')->unsigned()->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cranes', function (Blueprint $table) {
            $table->dropColumn([
                'rating_count','avg_rating'
            ]);
        });
    }
};
