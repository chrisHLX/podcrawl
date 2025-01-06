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
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->index(); // Link to podcast episode
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who added the transcript
            $table->string('episode_title')->nullable(); // 
            $table->longText('content')->nullable(); // Full transcript content (if provided at once)
            $table->longText('clean_text')->nullable(); // Cleaned transcript
            $table->integer('token_count')->nullable(); // Token count
            $table->decimal('generation_cost', 8, 2)->nullable(); // Cost estimate
            $table->timestamps();
        });
        DB::statement('ALTER TABLE transcripts ADD FULLTEXT(content)');
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
