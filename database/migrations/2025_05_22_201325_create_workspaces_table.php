<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id('workspace_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('workspace_type', ['Educational Center', 'Co-work Space']);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('profile_video')->nullable();
            $table->timestamps();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};