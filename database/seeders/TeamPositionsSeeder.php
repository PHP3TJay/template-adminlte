<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamPositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('team_positions')->insert(array (
            0 => array (
                'team_id' => '1',
                'title' => 'Admin',
                'hierarchy_level' => '0'
            ),
            1 => array (
                'team_id' => '2',
                'title' => 'Manager',
                'hierarchy_level' => '1'
            ),
            2 => array (
                'team_id' => '2',
                'title' => 'Team Leader',
                'hierarchy_level' => '2'
            ),
            3 => array (
                'team_id' => '2',
                'title' => 'Quality Assurance',
                'hierarchy_level' => '3'
            ),
            4 => array (
                'team_id' => '2',
                'title' => 'Agent',
                'hierarchy_level' => '4'
            )
        ));
    }
}
