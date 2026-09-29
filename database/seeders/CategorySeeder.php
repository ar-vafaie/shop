<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'name' => 'cat1',
            'img_path' => 'storage/images/categories/IMG_20230622_220054_334.jpg'
        ]);
        DB::table('categories')->insert([
            'name' => 'cat2',
            'img_path' => 'storage/images/categories/IMG_20240304_195618_065.jpg'
        ]);
    
    }
}
