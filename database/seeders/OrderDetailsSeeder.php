<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::query()->get();
        $products = Product::query()->get();

        if ($orders->isEmpty()) {
            $this->command->warn('No orders found. Please run OrderSeeder first.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSSeeder first.');
            return;
        }

        foreach ($orders as $order) {
            $numItems = fake()->numberBetween(1, 4);
            $selectedProducts = $products->random($numItems);

            foreach ($selectedProducts as $product) {
                $quantity = fake()->numberBetween(1, 5);
                $price = $product->price;
                $total = $price * $quantity;

                $productColor = $product->colors()->inRandomOrder()->first();

                OrderDetails::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_color_id' => $productColor?->id,
                    'color_name' => $productColor?->name,
                    'color_hex' => $productColor?->hex_code,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $total,
                ]);
            }
        }

        $this->command->info('Order details seeded successfully.');
    }
}
