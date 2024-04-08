<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(UserstableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(TeamPositionsSeeder::class);
        $this->call(TeamUsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleUsersTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);

        //  \App\Models\User::factory(200000)->create();
        //  \App\Models\TeamUser::factory(50000)->create();
        //  \App\Models\RoleUser::factory(50000)->create();
       
    }
}
