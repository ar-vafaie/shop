<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_images')->insert([
            'name' => 'IMG_20231106_192419_503.jpg',
            'product_id' => 1

        ]);
        DB::table('product_images')->insert([
            'name' => 'IMG_20231106_192419_503.jpg',
            'product_id' => 2
        ]);
    }
}
