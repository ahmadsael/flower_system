<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::query()->limit(5)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        $statuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];
        $paymentMethods = ['cash', 'card', 'wallet', 'online'];
        $farmerStatuses = ['pending', 'accepted', 'rejected'];

        foreach ($customers as $index => $customer) {
            $subtotal = fake()->randomFloat(2, 50, 500);
            $tax = $subtotal * 0.1;
            $discount = fake()->randomFloat(2, 0, 50);
            $total = $subtotal + $tax - $discount;

            Order::query()->create([
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => fake()->randomElement($statuses),
                'payment_method' => fake()->randomElement($paymentMethods),
                'payment_status' => fake()->randomElement($paymentStatuses),
                'shipping_address' => fake()->address(),
                'phone' => $customer->phone,
                'farmer_status' => fake()->randomElement($farmerStatuses),
                'rejection_reason' => fake()->randomElement($farmerStatuses) === 'rejected' ? fake()->sentence() : null,
                'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'updated_at' => fake()->dateTimeBetween('-30 days', 'now'),
            ]);
        }

        $this->command->info('Orders seeded successfully.');
    }
}
