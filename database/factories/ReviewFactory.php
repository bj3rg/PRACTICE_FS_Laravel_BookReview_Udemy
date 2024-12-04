<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-2 years');
        return [
            // columns that will be seeded with fake data
            'book_id' => null,
            'review' => fake()->paragraph,
            'rating' => fake()->numberBetween(1, 5),
            'created_at' => $createdAt,
            'updated_at' => fake()->dateTimeBetween($createdAt, 'now')
        ];
    }

    // Cast on state methods
    // Modify the generated data
    // What it does is it creates the same fake datas but differs with the selected attribute
    // This makes a diverse rating for each book reviews and not average
    public function good()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(4, 5)
            ];
        });
    }
    public function bad()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(1, 2)
            ];
        });
    }
    public function average()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => 3
            ];
        });
    }
}
