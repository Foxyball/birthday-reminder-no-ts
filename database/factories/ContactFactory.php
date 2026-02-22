<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'status' => fake()->boolean(),
            'name' => fake()->name(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'birthday' => fake()->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
            'image' => null,
            'notes' => fake()->optional()->paragraph(),
            'gift_ideas' => fake()->optional()->sentence(),
            'is_locked' => false,
            'locked_at' => null,
        ];
    }

    /**
     * Indicate that the contact is locked.
     */
    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_locked' => true,
            'locked_at' => now(),
        ]);
    }
}
