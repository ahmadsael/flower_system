<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Category;
use App\Models\Farmer;
use Illuminate\Support\Str;

class ProductSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farmer = Farmer::query()->first();
        if (! $farmer) {
            return;
        }

        $categories = Category::query()->pluck('id', 'slug');
        if ($categories->isEmpty()) {
            return;
        }

        $productsData = [
            [
                'name' => 'Red Roses Bouquet',
                'price' => 49.99,
                'cost_price' => 25.50,
                'stock' => 30,
                'sku' => 'RR-BOU-001',
                'status' => 'active',
                'category_slugs' => ['flowers', 'gifts'],
                'colors' => [
                    ['name' => 'Red', 'hex' => '#FF0000', 'stock' => 20, 'status' => 'active'],
                    ['name' => 'White', 'hex' => '#FFFFFF', 'stock' => 10, 'status' => 'active'],
                ],
            ],
            [
                'name' => 'Orchid Plant',
                'price' => 35.00,
                'cost_price' => 15.00,
                'stock' => 15,
                'sku' => 'ORC-PLT-002',
                'status' => 'active',
                'category_slugs' => ['plants'],
                'colors' => [
                    ['name' => 'Purple', 'hex' => '#800080', 'stock' => 8, 'status' => 'active'],
                ],
            ],
            [
                'name' => 'Gift Hamper Classic',
                'price' => 79.90,
                'cost_price' => 40.00,
                'stock' => 10,
                'sku' => 'GFT-HMP-003',
                'status' => 'inactive',
                'category_slugs' => ['gifts'],
                'colors' => [],
            ],
        ];

        foreach ($productsData as $data) {
            $slug = Str::slug($data['name']);
            $product = Product::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['name'] . ' description sample.',
                    'price' => $data['price'],
                    'cost_price' => $data['cost_price'],
                    'stock' => $data['stock'],
                    'sku' => $data['sku'],
                    'status' => $data['status'],
                    'created_by' => $farmer->id,
                ]
            );

            $categoryIds = collect($data['category_slugs'] ?? [])
                ->map(fn ($slugItem) => $categories[$slugItem] ?? null)
                ->filter()
                ->toArray();

            if (!empty($categoryIds)) {
                $product->categories()->sync($categoryIds);
            }

            if (!empty($data['colors'])) {
                $product->colors()->delete();
                foreach ($data['colors'] as $color) {
                    ProductColor::query()->create([
                        'product_id' => $product->id,
                        'name' => $color['name'],
                        'hex_code' => $color['hex'],
                        'stock' => $color['stock'],
                        'status' => $color['status'],
                    ]);
                }
            }
        }
    }
}
