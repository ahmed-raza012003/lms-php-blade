<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_certifications', function (Blueprint $table) {
            $table->id('certification_id');
            $table->unsignedBigInteger('teacher_id');
            $table->string('name');
            $table->string('file_path')->nullable(); // Single field for PDF or image
            $table->timestamps();

            $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_certifications');
    }
};
