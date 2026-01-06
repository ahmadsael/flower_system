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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');

            // Color (NEW)
            $table->unsignedBigInteger('product_color_id')->nullable();
            $table->string('color_name')->nullable();   // snapshot (e.g. Red)
            $table->string('color_hex')->nullable();    // snapshot (e.g. #FF0000)

            // Product snapshot
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);

            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('restrict');

            $table->foreign('product_color_id')
                  ->references('id')
                  ->on('product_colors')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
