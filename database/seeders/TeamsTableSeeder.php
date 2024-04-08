<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \DB::table('teams')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'ALL',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'PGED',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'SL Harbor',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'BOC',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Tollways',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'SMC Tel Ops',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Beer',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Corp',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Food',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'SMIS',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'SMITS',
                'description' => ' ',
                'logo' => 'assets/images/questionmarklogo.png', 
                'status' => '1',
            )
        ));
    }
}
