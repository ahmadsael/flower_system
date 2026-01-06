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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Basic category info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Category image
            $table->string('image')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Parent category (for sub-categories)
            $table->unsignedBigInteger('parent_id')->nullable();


            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            // Timestamps & soft delete
            $table->timestamps();
            $table->softDeletes();

    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
