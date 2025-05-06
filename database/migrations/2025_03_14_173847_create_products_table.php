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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('sku')->unique();
            $table->integer('stock');
            $table->string('brand');
            $table->string('category');
            $table->integer('base_price');
            $table->integer('sell_price');
            $table->char('unit', 5)->default('pc');
            $table->text('description')->nullable();
            $table->json('pictures')->nullable();
            $table->foreignId('submitted_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
