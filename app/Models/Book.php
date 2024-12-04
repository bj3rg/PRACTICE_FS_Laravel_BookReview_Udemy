<?php

namespace App\Models;


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
}
