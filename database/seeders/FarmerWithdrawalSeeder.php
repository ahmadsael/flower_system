<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\FarmerWallet;
use App\Models\FarmerWithdrawal;
use Illuminate\Database\Seeder;

class FarmerWithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallets = FarmerWallet::query()->with('farmer')->get();

        if ($wallets->isEmpty()) {
            $this->command->warn('No farmer wallets found. Please run FarmerWalletSeeder first.');

            return;
        }

        $admins = Admin::query()->get();
        $methods = ['bank_transfer', 'cash', 'wallet', 'paypal'];
        $statuses = ['pending', 'approved', 'rejected', 'processing', 'paid'];

        // Create 2-4 withdrawal requests per wallet
        foreach ($wallets as $wallet) {
            $numberOfWithdrawals = fake()->numberBetween(2, 4);

            for ($i = 0; $i < $numberOfWithdrawals; $i++) {
                $status = fake()->randomElement($statuses);
                $amount = fake()->randomFloat(2, 50, min(500, $wallet->available_amount + $wallet->pending_withdrawal_amount));

                // Determine if withdrawal should be processed
                $processedByAdminId = null;
                $processedAt = null;
                $rejectionReason = null;

                if (in_array($status, ['approved', 'rejected', 'processing', 'paid'])) {
                    if ($admins->isNotEmpty()) {
                        $processedByAdminId = $admins->random()->id;
                    }
                    $processedAt = fake()->dateTimeBetween('-20 days', 'now');
                }

                if ($status === 'rejected') {
                    $rejectionReasons = [
                        'Insufficient documentation provided',
                        'Account verification required',
                        'Invalid bank account details',
                        'Request exceeds withdrawal limit',
                        'Account under review',
                    ];
                    $rejectionReason = fake()->randomElement($rejectionReasons);
                }

                FarmerWithdrawal::query()->create([
                    'farmer_id' => $wallet->farmer_id,
                    'farmer_wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'method' => fake()->randomElement($methods),
                    'status' => $status,
                    'reference' => fake()->boolean(70) ? fake()->numerify('ACC#######') : null,
                    'note' => fake()->boolean(50) ? fake()->sentence() : null,
                    'rejection_reason' => $rejectionReason,
                    'processed_by_admin_id' => $processedByAdminId,
                    'processed_at' => $processedAt,
                    'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                    'updated_at' => $processedAt ?? fake()->dateTimeBetween('-30 days', 'now'),
                ]);
            }
        }

        $this->command->info('Farmer withdrawals seeded successfully.');
    }
}
