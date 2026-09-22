<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
        ]);

        Category::create([
            'name' => 'Clothing',
            'description' => 'Apparel and fashion items',
        ]);

        Category::create([
            'name' => 'Books',
            'description' => 'Books and educational materials',
        ]);

        Category::create([
            'name' => 'Home & Kitchen',
            'description' => 'Home and kitchen products',
        ]);
    }
}
