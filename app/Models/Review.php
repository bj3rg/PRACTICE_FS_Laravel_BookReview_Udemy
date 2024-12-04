<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //Add this line everytime you want to create a seeder
    use HasFactory;
    //
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
