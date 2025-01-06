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
        Schema::create('transcript_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transcript_id')->constrained()->onDelete('cascade'); // Link to parent transcript
            $table->string('title');
            $table->text('content');
            $table->string('created_by'); // created by either User ID or AI
            $table->timestamps();
        });
        
        DB::statement('ALTER TABLE transcript_sections ADD FULLTEXT(content)');
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('transcript_sections', function (Blueprint $table) {
            $table->dropForeign(['transcript_id']); // Replace 'transcript_id' with the actual foreign key column name
        });
    
        Schema::dropIfExists('transcript_sections');
    }
    
};
