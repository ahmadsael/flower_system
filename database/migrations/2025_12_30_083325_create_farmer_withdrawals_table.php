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
        Schema::create('farmer_withdrawals', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('farmer_id')
                ->constrained('farmers')
                ->onDelete('cascade');

            $table->foreignId('farmer_wallet_id')
                ->constrained('farmer_wallets')
                ->onDelete('cascade');

            // Withdrawal info
            $table->decimal('amount', 10, 2);

            $table->enum('method', [
                'bank_transfer',
                'cash',
                'wallet',
                'paypal',
            ]);

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'processing',
                'paid',
            ])->default('pending');

            $table->string('reference')->nullable();

            // Reason / notes
            $table->text('note')->nullable();
            $table->text('rejection_reason')->nullable();

            // Admin who processed the request
            $table->foreignId('processed_by_admin_id')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_withdrawals');
    }
};
