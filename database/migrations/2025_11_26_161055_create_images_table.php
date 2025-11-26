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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            // polymorphic fields
            $table->string('imageable_type');
            $table->unsignedBigInteger('imageable_id');

            // image data
            $table->string('filename');    // original filename or generated name
            $table->string('path');        // storage path (e.g. "images/abc.jpg")
            $table->string('mime_type')->nullable();
            $table->string('alt')->nullable();
            $table->unsignedInteger('size')->nullable(); // bytes

            $table->timestamps();

            $table->index(['imageable_type', 'imageable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
