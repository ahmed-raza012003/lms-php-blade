<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalCentersTable extends Migration
{
    public function up()
    {
        Schema::create('educational_centers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the educational center
            $table->text('description')->nullable(); // Description of the center
            $table->string('address'); // Physical address of the center
            $table->string('website')->nullable(); // Website URL (optional)
            $table->string('operating_hours')->nullable(); // Operating hours (optional)
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending'); // Verification status
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('educational_centers');
    }
}