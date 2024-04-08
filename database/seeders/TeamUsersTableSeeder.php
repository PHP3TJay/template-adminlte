<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('team_users')->insert(array (
            0 => 
            array (
                'user_id' => '1',
                'team_id' => '1',
                'team_position_id' => '1'
            ),
            1 => 
            array (
                'user_id' => '2',
                'team_id' => '1',
                'team_position_id' => '1'
            ),
            2 => 
            array (
                'user_id' => '3',
                'team_id' => '1',
                'team_position_id' => '1'
            ),
            3 => 
            array (
                'user_id' => '4',
                'team_id' => '1',
                'team_position_id' => '1'
            ),
            4 => 
            array (
                'user_id' => '5',
                'team_id' => '1',
                'team_position_id' => '1'
            ),
            5 => 
            array (
                'user_id' => '6',
                'team_id' => '1',
                'team_position_id' => '1'
            ),
            6 => 
            array (
                'user_id' => '7',
                'team_id' => '2',
                'team_position_id' => '2'
            ),
            7 => 
            array (
                'user_id' => '8',
                'team_id' => '2',
                'team_position_id' => '3'
            ),
            8 => 
            array (
                'user_id' => '9',
                'team_id' => '2',
                'team_position_id' => '4'
            ),
            9 => 
            array (
                'user_id' => '10',
                'team_id' => '2',
                'team_position_id' => '5'
            ),
            10 => 
            array (
                'user_id' => '11',
                'team_id' => '2',
                'team_position_id' => '5'
            )
        ));
    }
}
