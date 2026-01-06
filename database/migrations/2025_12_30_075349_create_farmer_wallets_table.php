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
        Schema::create('farmer_wallets', function (Blueprint $table) {
            $table->id();

            // Farmer relation
            $table->foreignId('farmer_id')
                ->constrained('farmers')
                ->onDelete('cascade');

            // Wallet balances
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('available_amount', 10, 2)->default(0);
            $table->decimal('pending_withdrawal_amount', 10, 2)->default(0);

            // Extra column (e.g. last transaction date)
            $table->timestamp('last_transaction_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_wallets');
    }
};
