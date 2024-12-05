@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Books</h1>

    {{-- A form that contains search and clear feature; Uses Get method to pass down the arguments to the books.index method inside controller --}}
    <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
        <input class="input h-10" type="text" name="title" placeholder="Search by title" value="{{ request('title') }}">
        {{-- Allows retaining the current filter whenever submitting button --}}
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        {{-- Submit button --}}
        <input class="btn h-10" type="submit" value="Search">
        {{-- Reload the page to clear filtering --}}
        <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
    </form>

    <div class="filter-container mb-4 flex">
        @php
            //Declare a list of key value for popularity list, declared in array so it can be used within the session when getting through request; e.g. request('filter')
            $filters = [
                '' => 'Latest',
                'popular_last_month' => 'Popular Last Month',
                'popular_last_6month' => 'Popular Last 6 Months',
                'highest_rated_last_month' => 'Highest Rated Last Month',
                'highest_rated_last_6month' => 'Highest Rated Last 6 Months',
            ];
        @endphp
        {{-- List down all the filtering way --}}
        @foreach ($filters as $key => $filter)
            {{-- in href, the query parameter can be defined in an array[] and adding variable and its value --}}
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}" {{-- class takes out the var -> filter which denoted as $key, then whenever the current key or if filter is blank, it will run the ternary operator --}}
                class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
                {{ $filter }}
            </a>
        @endforeach
    </div>

    <ul>
        {{-- List down all the books, as $books is passed as data in controller, it is then received here and displayed --}}
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">{{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                {{ number_format($book->reviews_avg_rating, 1) }}
                            </div>
                            <div class="book-review-count">
                                out of {{ number_format($book->reviews_count) }}
                                {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            {{-- No boos in db, render this --}}
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
