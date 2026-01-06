<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\FarmerWallet;
use Illuminate\Database\Seeder;

class FarmerWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farmers = Farmer::query()->get();

        if ($farmers->isEmpty()) {
            $this->command->warn('No farmers found. Please run FarmerSeeder first.');

            return;
        }

        foreach ($farmers as $farmer) {
            // Check if wallet already exists
            $existingWallet = FarmerWallet::query()->where('farmer_id', $farmer->id)->first();

            if ($existingWallet) {
                continue;
            }

            // Generate random wallet amounts
            $totalAmount = fake()->randomFloat(2, 500, 5000);
            $pendingAmount = fake()->randomFloat(2, 0, min(500, $totalAmount * 0.3));
            $availableAmount = $totalAmount - $pendingAmount;

            FarmerWallet::query()->create([
                'farmer_id' => $farmer->id,
                'total_amount' => $totalAmount,
                'available_amount' => $availableAmount,
                'pending_withdrawal_amount' => $pendingAmount,
                'last_transaction_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'created_at' => fake()->dateTimeBetween('-60 days', '-30 days'),
                'updated_at' => fake()->dateTimeBetween('-30 days', 'now'),
            ]);
        }

        $this->command->info('Farmer wallets seeded successfully.');
    }
}
