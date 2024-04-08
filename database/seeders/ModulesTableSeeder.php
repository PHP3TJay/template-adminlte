<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('modules')->insert(array (
            0 => 
            array (
                'name' => 'User',
                'path' => '/user',
                'controller' => 'UserController',
                'function' => 'index',
                'method' => 'get'
            ),
            1 => array (
                'name' => 'Team',
                'path' => '/team',
                'controller' => 'TeamController',
                'function' => 'index',
                'method' => 'get'
            ),
            2 => array (
                'name' => 'Coaching',
                'path' => '/coaching',
                'controller' => 'CoachingController',
                'function' => 'index',
                'method' => 'get'
            ),
            3 => array (
                'name' => 'Coaching',
                'path' => '/coaching2',
                'controller' => 'CoachingController',
                'function' => 'coaching2',
                'method' => 'get'
            )
        ));
    }
}
