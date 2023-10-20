<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


            $arr[] = array('title'=>'King', 'isActive' => true);
            $arr[] = array('title'=>'Queen', 'isActive' => true);
            $arr[] = array('title'=>'Double', 'isActive' => true);
            $arr[] = array('title'=>'Twin Sharing', 'isActive' => true);
            $arr[] = array('title'=>'Single', 'isActive' => true);
            $arr[] = array('title'=>'Family', 'isActive' => true);

            DB::table('bed_types')->insert($arr);


    }
}
