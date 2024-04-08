<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'middlename' => '',
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'account_status' => 'active',
            'email_verified_at' => now(),
            'password' => '$2y$10$8IevdFeijB4ta1582swKAeQ1.GtA56U8jxX3kNaJuaRPSc/mFUTM2', // Your hashed password
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'photo' => ' ',
        ];
    }
}
