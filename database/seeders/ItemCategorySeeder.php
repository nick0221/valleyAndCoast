<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventoryCategories = [
            ['categoryTitle' => 'Bedroom Furniture'],
            ['categoryTitle' => 'Bathroom Supplies'],
            ['categoryTitle' => 'Linens and Bedding'],
            ['categoryTitle' => 'Kitchen and Dining'],
            ['categoryTitle' => 'Housekeeping Supplies'],
            ['categoryTitle' => 'Electronics and Appliances'],
            ['categoryTitle' => 'Guest Amenities'],
            ['categoryTitle' => 'Room Decor and Accessories'],
            ['categoryTitle' => 'Office Supplies'],
            ['categoryTitle' => 'Maintenance Tools'],
            ['categoryTitle' => 'Outdoor and Recreational Equipment'],
            ['categoryTitle' => 'Uniforms and Attire']
        ];

        DB::table('item_categories')->insert($inventoryCategories);
    }
}
