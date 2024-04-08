<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'name' => 'Counseling',
                'status' => '1'
            ),
            1 => 
            array (
                'name' => 'Kamustahan',
                'status' => '1'
            ),
            2 => 
            array (
                'name' => 'Mentoring',
                'status' => '1'
            ),
            3 => 
            array (
                'name' => 'Performance Mentoring',
                'status' => '1'
            )
        ));
    }
}
