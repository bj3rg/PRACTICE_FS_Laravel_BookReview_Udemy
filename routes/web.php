<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::fallback(function () {
    return "Error 404";
});

require __DIR__ . '/BookRoutes.php';
