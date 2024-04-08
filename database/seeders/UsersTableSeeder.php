<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'middlename' => '',
                'username' => 'superadmin', 
                'email' => 'superadmin@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55', 
                'photo' => ' '
            ),
            1 => array(
                'id' => 2,
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'middlename' => '',
                'username' => 'admin', 
                'email' => 'admin@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            2 => array(
                'id' => 3,
                'firstname' => 'Manager',
                'lastname' => 'Manager',
                'middlename' => '',
                'username' => 'manager', 
                'email' => 'manager@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            3 => array(
                'id' => 4,
                'firstname' => 'Team',
                'lastname' => 'Leader',
                'middlename' => '',
                'username' => 'teamleader', 
                'email' => 'teamleader@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            4 => array(
                'id' => 5,
                'firstname' => 'Quality',
                'lastname' => 'Assurance',
                'middlename' => '',
                'username' => 'qualityassurance', 
                'email' => 'qualityassurance@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            5 => array(
                'id' => 6,
                'firstname' => 'Agent',
                'lastname' => 'Agent',
                'middlename' => '',
                'username' => 'agent', 
                'email' => 'agent@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            6 => array(
                'id' => 7,
                'firstname' => 'PGEDM',
                'lastname' => 'PGEDM',
                'middlename' => '',
                'username' => 'pgedm', 
                'email' => 'pgedm@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            7 => array(
                'id' => 8,
                'firstname' => 'PGEDTL',
                'lastname' => 'PGEDTL',
                'middlename' => '',
                'username' => 'pgedtl', 
                'email' => 'pgedtl@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            8 => array(
                'id' => 9,
                'firstname' => 'PGEDQA',
                'lastname' => 'PGEDQAA',
                'middlename' => '',
                'username' => 'pgedqa', 
                'email' => 'pgedqa@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            9 => array(
                'id' => 10,
                'firstname' => 'PGEDA',
                'lastname' => 'PGEDA',
                'middlename' => '',
                'username' => 'pgeda', 
                'email' => 'pgeda@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' ' 
            ),
            10 => array(
                'id' => 11,
                'firstname' => 'PGEDA2',
                'lastname' => 'PGEDA2',
                'middlename' => '',
                'username' => 'pgeda2', 
                'email' => 'pgeda2@gmail.com',
                'account_status' => 'active',
                'email_verified_at' => '2021-03-11 16:25:49',
                'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2',
                'remember_token' => NULL,
                'created_at' => '2020-09-05 06:32:55',
                'updated_at' => '2020-09-05 06:32:55',
                'photo' => ' '
            )
        ));
    }
}
