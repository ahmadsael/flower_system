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
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->unsignedBigInteger('product_id');

            // Color info
            $table->string('name');          // e.g. Red, White, Yellow
            $table->string('hex_code')->nullable(); // e.g. #FF0000

            // Optional stock per color
            $table->integer('stock')->default(0);

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Timestamps
            $table->timestamps();

            // Foreign key
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};
