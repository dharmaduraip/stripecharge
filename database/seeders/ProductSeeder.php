<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Product 1',
            'price' => 20.00,
            'description' => 'Description for Product 1',
        ]);

        Product::create([
            'name' => 'Product 2',
            'price' => 35.00,
            'description' => 'Description for Product 2',
        ]);
    }
}
