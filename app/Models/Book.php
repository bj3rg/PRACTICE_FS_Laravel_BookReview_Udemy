<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //Add this line everytime you want to create a seeder
    use HasFactory;
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    //Query for searching using title parameter
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    // Search through popular by having a date range and checking through total reviews_count
    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
        // search through the date range with regards to total reviews_count
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])
            ->orderBy('reviews_count', 'desc');
    }

    // Search through highestrated book review by having a date range and checking the reviews_avg_rating
    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
    {
        // search through the date range but with regards to the average(withAvg) rating of the books
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    // It accept an arg that checks if the listed books in popular/highestrated has greaterthan or equal to the minReviews required
    public function scopeMinReviews(Builder $query, int $minReviews): Builder
    {
        // Having is used because we are working with the result of aggregate functions
        // Note: aggregate functions operates with count, sum, avg, max, min
        return $query->having('reviews_count', '>=', $minReviews);
    }

    // A private function that accepts two date, from and to, it checks on which condition the args satisfies
    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        // if from is only added, it searches starting from up to the recent (greater than)
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
            // if to is only added, it searches starting to then up to the older (less than)
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
            // if both exist, then it searches within the timeline
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }
}
