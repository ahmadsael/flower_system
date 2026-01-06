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

            // Basic product info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();

            // Stock & SKU
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();

            // Image
            $table->string('image')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Owner (farmer / admin)
            $table->foreignId('created_by')->nullable()->constrained('farmers')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
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
