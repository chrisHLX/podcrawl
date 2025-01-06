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
        Schema::create('word_dictionary', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->unsignedBigInteger('user_id')->nullable(); // doesnt need to be a foreign key if user gets deleted word can stay in dictionary
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('word_dictionary');
    }
};
