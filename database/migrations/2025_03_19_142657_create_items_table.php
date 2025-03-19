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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->integer('quantity_committed')->default(0);
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('quantity_on_order')->default(0);
            $table->integer('quantity_available')->default(0);


            $table->decimal('average_cost', 10, 2)->default(0);
            $table->decimal('current_value', 10, 2)->default(0);
            $table->decimal('base_selling_price', 10, 2)->default(0);

            $table->boolean('is_bought')->default(false);
            $table->boolean('is_sold')->default(false);
            $table->boolean('is_inventoried')->default(false);

            $table->string('cost_of_sales_account')->nullable();

            $table->string('asset_account')->nullable();
            $table->text('location_details')->nullable();
            $table->string('default_sell_location')->nullable();
            $table->string('default_receive_location')->nullable();
            $table->timestamp('last_modified')->nullable();
            $table->string('photo_uri')->nullable();
            $table->string('uri')->nullable();
            $table->string('row_version')->nullable();

            $table->json('custom_list_1')->nullable();
            $table->string('custom_list_2')->nullable();
            $table->string('custom_list_3')->nullable();
            $table->string('custom_field_1')->nullable();
            $table->string('custom_field_2')->nullable();
            $table->string('custom_field_3')->nullable();

            $table->json('expense_account')->nullable();
            $table->json('income_account')->nullable();
            $table->text('buying_details')->nullable();
            $table->json('selling_details')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
