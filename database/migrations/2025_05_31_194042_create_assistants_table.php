<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('assistants');
        
        Schema::create('assistants', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('assistant_id');
            $table->unsignedBigInteger('assistant_user_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('authority_level', ['full', 'limited'])->default('limited');
            $table->timestamps();

            $table->foreign('assistant_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['assistant_user_id', 'user_id'], 'assistant_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistants');
    }
};