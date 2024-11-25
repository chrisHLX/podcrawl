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
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('publisher');
            $table->unsignedBigInteger('host_id')->nullable();
            $table->text('description')->nullable();
            $table->string('genre')->nullable();
            $table->string('spotify_id')->unique();
            $table->string('image_url')->nullable();
            $table->string('spotify_url')->nullable();
            $table->timestamps();
            // Foreign key -- if the host gets deleted then in the shows field the host will be set to null
            $table->foreign('host_id')->references('id')->on('people')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};
