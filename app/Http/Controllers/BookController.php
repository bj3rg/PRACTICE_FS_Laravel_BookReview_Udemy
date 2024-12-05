<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Get input from user
        $title = $request->input('title');
        //Check what user wants to filter
        $filter = $request->input('filter', '');

        //Conditional query using when, if title exists, then function runs
        $books = Book::when($title, function ($query, $title) {
            return $query->title($title);
        });

        // Use a switch-case type; it gets the value of filter then it calls the query function for the selected one's
        $books = match ($filter) {

            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6month' => $books->popularLast6Month(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6month' => $books->highestRatedLast6Month(),
            default => $books->latest()
        };

        // $books = $books->get();
        $cacheKey = 'books:' . $filter . ':' . $title;
        $books = cache()->remember($cacheKey, 3600, fn() => $books->get());


        return view('books.index', ['books' => $books, 'filter' => $filter]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // Generate a unique cache key for the current book using its ID
        $cacheKey = 'book:' . $book->id;

        // Store or retrieve the book and its reviews from the cache for 1 hour (3600 seconds)
        // If not already cached, load the book with its reviews sorted by the most recent
        $book = cache()->remember($cacheKey, 3600, fn() =>  $book->load([
            'reviews' => fn($query) => $query->latest()
        ]));

        // Returns the current book data; it has a sorting mechanism for reviews via recent or latest review
        return view('books.show', ['book' => $book]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
