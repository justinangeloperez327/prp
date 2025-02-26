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
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->string('product_item_uid')->nullable();
            $table->string('size')->nullable();
            $table->string('unit')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('gsm')->nullable();
            $table->string('type')->nullable();
            $table->integer('sheets_per_mill_pack')->nullable();
            $table->integer('sheets_per_pallet')->nullable();
            $table->decimal('price_per_quantity', 10, 2)->default(0.00);
            $table->decimal('price_broken_mill_pack', 10, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('product_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};
