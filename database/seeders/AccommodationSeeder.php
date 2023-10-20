<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = array(
            array(
                'roomNumber' => "HAWAII",
                'bedCount' => 2,
                'bed_type_id' => 1,
                'maxOccupancy' => 3,
                'pricePerNight' => '4280',
                'availability' =>  true,
                'isAirconditioned' =>  true,
                'isSmokingAllowed' =>  false,
                'hasBalcony' =>  true,

            ),

            array(
                'roomNumber' => "ALASKA",
                'bedCount' => 2,
                'bed_type_id' => 2,
                'maxOccupancy' => 3,
                'pricePerNight' => '4380',
                'availability' =>  true,
                'isAirconditioned' =>  false,
                'isSmokingAllowed' =>  false,
                'hasBalcony' =>  false,

            ),

        );
        DB::table('accommodations')->insert($arr);
    }
}
