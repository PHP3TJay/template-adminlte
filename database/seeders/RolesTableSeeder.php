<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
    {
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'name' => 'Superadmin',
                'description' => 'Dev account'
            ),
            1 => 
            array (
                'name' => 'Admin',
                'description' => 'Admininstrator account with the same like authorization as the dev'
            ),
            2 => 
            array (
                'name' => 'Manager',
                'description' => 'Manager of Teams'
            ),
            3 => 
            array (
                'name' => 'Team Leader',
                'description' => 'Team leader for the teams'
            ),
            4 => 
            array (
                'name' => 'Quality Assurance',
                'description' => 'Quality Assurance for the team'
            ),
            5 => 
            array (
                'name' => 'Agent',
                'description' => 'Agent in the team'
            )
        ));
    }
}
