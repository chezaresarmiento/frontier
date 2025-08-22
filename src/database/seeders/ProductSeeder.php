<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            ['name' => 'iPhone 15', 'price' => 999.99, 'description' => 'Apple smartphone'],
            ['name' => 'MacBook Pro', 'price' => 1999.00, 'description' => 'Apple laptop'],
            ['name' => 'AirPods Pro', 'price' => 249.00, 'description' => 'Apple earbuds'],
        ]);
    }
}
