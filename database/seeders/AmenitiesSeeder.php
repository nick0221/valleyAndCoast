<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = array(
            array('title' => 'Free Wi-Fi', 'isAvailable' => true),
            array('title' => 'Parking', 'isAvailable' => true),
            array('title' => 'Airport Shuttle', 'isAvailable' => true),
            array('title' => 'Breakfast', 'isAvailable' => true),
            array('title' => 'Swimming Pool', 'isAvailable' => true),
            array('title' => 'Fitness Center/Gym', 'isAvailable' => true),
            array('title' => 'Restaurant/Bar', 'isAvailable' => true),
            array('title' => 'Room Service', 'isAvailable' => true),
            array('title' => 'Air Conditioning', 'isAvailable' => true),
            array('title' => 'Heating', 'isAvailable' => true),
            array('title' => 'Flat-Screen TV', 'isAvailable' => true),
            array('title' => 'Cable/Satellite Channels', 'isAvailable' => true),
            array('title' => 'Mini Fridge', 'isAvailable' => true),
            array('title' => 'Coffee/Tea Maker', 'isAvailable' => true),
            array('title' => 'Hair Dryer', 'isAvailable' => true),
            array('title' => 'Iron/Ironing Board', 'isAvailable' => true),
            array('title' => 'Safe Deposit Box', 'isAvailable' => true),
            array('title' => 'Desk/Workspace', 'isAvailable' => true),
            array('title' => 'Private Bathroom', 'isAvailable' => true),
            array('title' => 'Toiletries', 'isAvailable' => true),
            array('title' => 'Towels/Linens', 'isAvailable' => true),
            array('title' => 'Daily Housekeeping', 'isAvailable' => true),
            array('title' => 'Laundry Facilities/Service', 'isAvailable' => true),
            array('title' => 'Luggage Storage', 'isAvailable' => true),
            array('title' => 'Concierge Service', 'isAvailable' => true),
            array('title' => 'Elevator/Lift', 'isAvailable' => true),
            array('title' => 'Non-Smoking Rooms/Floors', 'isAvailable' => true),
            array('title' => 'Family Rooms', 'isAvailable' => true),
            array('title' => 'Pet-Friendly', 'isAvailable' => true),
            array('title' => 'Wheelchair Accessible', 'isAvailable' => true)
        );

        DB::table('amenities')->insert($amenities);


    }
}
