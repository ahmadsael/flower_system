<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roots = [
            [
                'name' => 'Flowers',
                'description' => 'All kinds of fresh flowers.',
            ],
            [
                'name' => 'Plants',
                'description' => 'Indoor and outdoor plants.',
            ],
            [
                'name' => 'Gifts',
                'description' => 'Gift items and accessories.',
            ],
        ];

        $created = [];

        foreach ($roots as $root) {
            $slug = Str::slug($root['name']);

            $category = Category::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $root['name'],
                    'description' => $root['description'] ?? null,
                    'status' => 'active',
                    'created_by' => null,
                ]
            );

            $created[$slug] = $category->id;
        }

        $children = [
            [
                'name' => 'Roses',
                'parent' => 'flowers',
                'description' => 'Bouquets and single-stem roses.',
            ],
            [
                'name' => 'Lilies',
                'parent' => 'flowers',
                'description' => 'Oriental and Asiatic lilies.',
            ],
            [
                'name' => 'Succulents',
                'parent' => 'plants',
                'description' => 'Low-maintenance succulent plants.',
            ],
            [
                'name' => 'Orchids',
                'parent' => 'plants',
                'description' => 'Phalaenopsis and more orchid varieties.',
            ],
            [
                'name' => 'Gift Hampers',
                'parent' => 'gifts',
                'description' => 'Curated hampers with treats and flowers.',
            ],
        ];

        foreach ($children as $child) {
            $slug = Str::slug($child['name']);
            $parentSlug = $child['parent'];
            $parentId = $created[$parentSlug] ?? null;

            if ($parentId === null) {
                continue;
            }

            Category::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $child['name'],
                    'description' => $child['description'] ?? null,
                    'status' => 'active',
                    'parent_id' => $parentId,
                    'created_by' => null,
                ]
            );
        }
    }
}
