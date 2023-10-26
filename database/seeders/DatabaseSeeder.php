<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BedTypeSeeder::class,
            AmenitiesSeeder::class,
            AccommodationSeeder::class,
            DesignationSeeder::class,
            ItemCategorySeeder::class,

        ]);

         \App\Models\Customer::factory(10)->create();
         \App\Models\Staff::factory(10)->create();
         \App\Models\SupplierProfile::factory(10)->create();
         \App\Models\User::factory()->create([
             'name' => 'DefaultUser',
             'email' => 'default@admin.com',
         ]);
    }
}
