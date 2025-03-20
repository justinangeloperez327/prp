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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->nullable();
            $table->string('company');

            $table->string('email')->nullable();

            $table->string('phone')->nullable();
            $table->string('fax')->nullable();

            $table->string('website')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();

            // address
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->enum('state', ['ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA'])->default('VIC');
            $table->string('postcode')->nullable();
            $table->string('country')->default('Australia');

            // delivery details
            $table->enum('apply_delivery_charge', ['none', 'fixed', 'minimum-order'])->default('none');
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->decimal('charge_trigger', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
