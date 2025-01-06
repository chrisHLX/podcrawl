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
        //
        Schema::create('episode_user_topics', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->index(); // Link to podcast episode
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who added the transcript
            $table->string('topic')->unique(); // Topic a user wants to add has to be unique
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
