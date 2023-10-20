<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $designationTitles = [
            ['designationTitle' => 'General Manager'],
            ['designationTitle' => 'Assistant General Manager'],
            ['designationTitle' => 'Front Office Manager'],
            ['designationTitle' => 'Front Desk Agent/Receptionist'],
            ['designationTitle' => 'Concierge'],
            ['designationTitle' => 'Night Auditor'],
            ['designationTitle' => 'Housekeeping Manager'],
            ['designationTitle' => 'Housekeeping Supervisor'],
            ['designationTitle' => 'Room Attendant/Housekeeper'],
            ['designationTitle' => 'Laundry Attendant'],
            ['designationTitle' => 'Bellhop/Porter'],
            ['designationTitle' => 'Food and Beverage Manager'],
            ['designationTitle' => 'Restaurant Manager'],
            ['designationTitle' => 'Bartender'],
            ['designationTitle' => 'Server/Waiter/Waitress'],
            ['designationTitle' => 'Sous Chef'],
            ['designationTitle' => 'Chef de Partie'],
            ['designationTitle' => 'Pastry Chef'],
            ['designationTitle' => 'Catering Manager'],
            ['designationTitle' => 'Sales and Marketing Manager'],
            ['designationTitle' => 'Events Coordinator'],
            ['designationTitle' => 'Spa Manager'],
            ['designationTitle' => 'Fitness Center/Gym Attendant'],
            ['designationTitle' => 'Security Officer'],
            ['designationTitle' => 'Maintenance Engineer'],
            ['designationTitle' => 'Accounting/Finance Personnel'],
            ['designationTitle' => 'Human Resources Manager'],
            ['designationTitle' => 'IT Manager'],
            ['designationTitle' => 'Marketing and Public Relations Coordinator'],
            ['designationTitle' => 'Guest Relations Manager']
        ];

        DB::table('designations')->insert($designationTitles);
    }
}
