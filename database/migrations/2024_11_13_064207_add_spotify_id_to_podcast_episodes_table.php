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
        Schema::table('podcast_episodes', function (Blueprint $table) {
            //
    
            $table->string('spotify_id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->string('language')->nullable();
            $table->string('show_name')->nullable();
            $table->string('image_url')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            //
        });
    }
};
