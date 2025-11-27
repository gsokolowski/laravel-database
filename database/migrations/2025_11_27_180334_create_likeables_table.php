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
        Schema::create('likeables', function (Blueprint $table) {
            $table->id();
            // the user who likes
            $table->unsignedBigInteger('user_id')->index();
            // polymorphic target
            $table->unsignedBigInteger('likeable_id')->index();
            $table->string('likeable_type')->index();
            $table->timestamps();

            // optional foreign key constraint (commented — add if you want)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // unique constraint to prevent duplicate likes (user liking same model twice)
            $table->unique(['user_id', 'likeable_id', 'likeable_type'], 'likeables_unique_user_like');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likeables');
    }
};
