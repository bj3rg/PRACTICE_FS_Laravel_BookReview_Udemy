<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //Add this line everytime you want to create a seeder
    use HasFactory;
    //
    protected $fillable =
    [
        'review',
        'rating'
    ];
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted()
    {
        // Clear the cache for the associated book when a review is updated
        static::updated(fn(Review $review) => cache()->forget('book:' . $review->book_id));

        // Clear the cache for the associated book when a review is deleted
        static::deleted(fn(Review $review) => cache()->forget('book:' . $review->book_id));
    }
}
