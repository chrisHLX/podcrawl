<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('people_podcast_episode', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('people_id');
            $table->unsignedBigInteger('podcast_episode_id');
            $table->enum('role', ['guest', 'host']);
            $table->timestamps();
    
            // Foreign keys
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('podcast_episode_id')->references('id')->on('podcast_episodes')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('people_podcast_episode');
    }
};
