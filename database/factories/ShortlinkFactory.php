<?php

namespace Database\Factories;

use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shortlink>
 */
class ShortlinkFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'destination_url' => fake()->url(),
            'short_code' => fake()->unique()->regexify('[a-z0-9]{12}'),
            'click_count' => 0,
            'is_active' => true,
            'expires_at' => null,
        ];
    }
}
