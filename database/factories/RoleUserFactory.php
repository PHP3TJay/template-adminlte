<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RoleUser;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoleUser>
 */
class RoleUserFactory extends Factory
{
    protected $model = RoleUser::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 200000),
            'role_id' => $this->faker->numberBetween(1, 6), // Assuming there are 6 roles
            'team_id' => $this->faker->numberBetween(1, 3), // Assuming there are 3 teams
        ];
    }
}
