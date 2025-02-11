<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \DB::table('role_users')->insert(array (
            0 => 
            array (
                'user_id' => '1',
                'role_id' => '1',
                'team_id' => '1'
            ),
            1 => 
            array (
                'user_id' => '2',
                'role_id' => '2',
                'team_id' => '1'
            ),
            2 => 
            array (
                'user_id' => '3',
                'role_id' => '3',
                'team_id' => '1'
            ),
            3 => 
            array (
                'user_id' => '4',
                'role_id' => '4',
                'team_id' => '1'
            ),
            4 => 
            array (
                'user_id' => '5',
                'role_id' => '5',
                'team_id' => '1'
            ),
            5 => 
            array (
                'user_id' => '6',
                'role_id' => '6',
                'team_id' => '3'
            ),
            6 => 
            array (
                'user_id' => '7',
                'role_id' => '3',
                'team_id' => '3'
            ),
            7 => 
            array (
                'user_id' => '8',
                'role_id' => '4',
                'team_id' => '3'
            ),
            8 => 
            array (
                'user_id' => '9',
                'role_id' => '5',
                'team_id' => '3'
            ),
            9 => 
            array (
                'user_id' => '10',
                'role_id' => '6',
                'team_id' => '3'
            ),
            10 => 
            array (
                'user_id' => '11',
                'role_id' => '6',
                'team_id' => '3'
            )
        ));
    }
}
