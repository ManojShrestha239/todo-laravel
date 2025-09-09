<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dueDate = $this->faker->optional(0.7)->dateTimeBetween('now', '+30 days');

        return [
            'title' => $this->faker->sentence(rand(3, 8)),
            'description' => $this->faker->optional(0.6)->paragraph(rand(1, 3)),
            'due_date' => $dueDate ? $dueDate->format('Y-m-d') : null,
            'completed' => $this->faker->boolean(30), // 30% chance of being completed
            'created_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ];
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'completed' => true,
        ]);
    }

    /**
     * Indicate that the task is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn(array $attributes) => [
            'due_date' => $this->faker->dateTimeBetween('-10 days', '-1 day')->format('Y-m-d'),
            'completed' => false,
        ]);
    }
}
