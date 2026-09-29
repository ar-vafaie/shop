<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'pro1',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut deserunt velit sed, facere numquam optio repudiandae dolorum consequuntur cumque nobis doloribus eveniet voluptas earum corrupti eius vitae aliquid? Nam, ab!',
            'stock' => 50,
            'price' => 199.9,
            'buy_count' => 5
        ]);
        DB::table('products')->insert([
            'name' => 'pro2',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut deserunt velit sed, facere numquam optio repudiandae dolorum consequuntur cumque nobis doloribus eveniet voluptas earum corrupti eius vitae aliquid? Nam, ab!',
            'stock' => 59,
            'price' => 19.9,
            'buy_count' => 7
        ]);
        DB::table('category_product')->insert([
            'category_id' => 1,
            'product_id' => 1
        ]);
        DB::table('category_product')->insert([
            'category_id' => 2,
            'product_id' => 2
        ]);
    }
}
