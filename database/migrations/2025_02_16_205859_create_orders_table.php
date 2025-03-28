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
            $table->string('order_no')->unique();
            $table->date('order_date');
            $table->time('order_time');

            $table->date('would_like_it_by')->nullable();
            $table->date('dispatch_date')->nullable();
            $table->enum('status', ['draft', 'new', 'processed', 'cancelled', 'overdue', 'on-hold', 'deleted'])->nullable();

            $table->string('purchase_order_no')->nullable();
            $table->decimal('total', 10, 2)->default(0);

            $table->text('additional_instructions')->nullable();

            $table->foreignId('contact_id')->nullable();
            $table->foreignId('customer_id')->constrained();
            $table->json('myob_payload')->nullable();

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
