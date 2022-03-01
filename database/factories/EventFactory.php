<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'event_title' => $this->faker->title,
            'event_start_date' => now(),
            'event_end_date' => now()->addHours(11),
            'organization_id' => User::factory()->create()->id,
        ];
    }
}
