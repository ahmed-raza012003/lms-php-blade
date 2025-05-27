<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->unsignedBigInteger('workspace_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price_per_hour', 8, 2);
            $table->integer('capacity');
            $table->string('profile_photo')->nullable();
            $table->string('profile_video')->nullable();
            $table->timestamps();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->foreign('workspace_id')->references('workspace_id')->on('workspaces')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
