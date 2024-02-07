<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteQuoteController extends Controller
{
    public function index()
    {
        return Inertia::render('Quotes/Favorites/List', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
        ]);
    }

    public function report()
    {
        return Inertia::render('Quotes/Favorites/Report', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
        ]);
    }
}
