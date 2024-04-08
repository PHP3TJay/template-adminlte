<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TeamUser;

class TeamUserFactory extends Factory
{
    protected $model = TeamUser::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 200000),
            'team_id' => $this->faker->numberBetween(1, 3), // Assuming there are 3 teams
        ];
    }
}
