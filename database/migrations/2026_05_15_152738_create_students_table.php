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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Personal Info
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birthdate');
            $table->string('birthplace')->nullable();
            
            // Contact & Address
            $table->text('address');
            $table->string('contact_number')->nullable();
            
            // Guardian Info
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_contact')->nullable();
            
            // School Info
            $table->string('lrn')->unique()->nullable(); // Learner Reference Number
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'graduated', 'dropped', 'transferred'])->default('active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
