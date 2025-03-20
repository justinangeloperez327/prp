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
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('contact_code')->nullable();
            $table->enum('title', ['Mr', 'Mrs', 'Ms', 'Miss', 'Dr'])->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('direct_phone')->nullable();
            $table->string('mobile_phone')->nullable();

            $table->string('email')->nullable();

            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
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
