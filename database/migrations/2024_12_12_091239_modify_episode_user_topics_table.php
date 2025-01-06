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
        Schema::table('episode_user_topics', function (Blueprint $table) {
            $table->dropUnique(['topic']); // Drop the global unique constraint
            $table->unique(['spotify_id', 'topic']); // Add composite unique index
        });
    }
    
    public function down()
    {
        Schema::table('episode_user_topics', function (Blueprint $table) {
            $table->dropUnique(['spotify_id', 'topic']); // Drop composite index
            $table->unique('topic'); // Re-add global unique constraint
        });
    }
    
};
