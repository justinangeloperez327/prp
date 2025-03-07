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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_no');
            $table->date('order_date');
            $table->time('order_time');
            $table->date('would_like_it_by')->nullable();
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('additional_instructions')->nullable();
            $table->enum('status', ['draft', 'new', 'processing', 'cancelled', 'overdue', 'on-hold'])->default('draft');
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
