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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('serial');
            $table->string('customer_name');
            $table->string('customer_address');
            $table->integer('total_items');
            $table->integer('total_quantity');
            $table->bigInteger('total_price');
            $table->integer('tax');
            $table->bigInteger('cash_in')->default(0);
            $table->integer('change')->default(0);
            $table->boolean('is_debt')->default(false);
            $table->boolean('is_debt_paid')->default(0);
            $table->foreignId('submitted_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->string('name');
            $table->integer('quantity');
            $table->integer('price');
            $table->char('unit', 5);
            $table->foreignId('product_id')->constrained('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_items');
    }
};
