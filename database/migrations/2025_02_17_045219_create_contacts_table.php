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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('contact_no')->nullable();
            $table->enum('title', ['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Prof', 'Rev', 'Other'])->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('direct_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('email');
            $table->foreignId('customer_id')->constrained();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Log in Details
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
