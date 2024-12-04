<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use App\Models\Book;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Book::factory(33)->create()->each(function ($book) {

            // Number of reviews using randomizer
            $numReviews = random_int(5, 30);

            // Counts down the total reviews for current book
            Review::factory()->count($numReviews)
                // Determines what type of review it will have; state method
                // Declared on ReviewFactory
                ->good()
                // This line assigns the list of Reviews to the current $book
                ->for($book)
                // Creates the review
                ->create();

            Review::factory()->count($numReviews)
                ->average()
                ->for($book)
                ->create();

            Review::factory()->count($numReviews)
                ->bad()
                ->for($book)
                ->create();
        });



        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
