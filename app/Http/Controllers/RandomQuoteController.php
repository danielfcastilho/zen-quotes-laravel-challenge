<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RandomQuoteController extends Controller
{
    public function index()
    {
        return Inertia::render('Quotes/Random/List', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
        ]);
    }

    public function secureIndex()
    {
        return Inertia::render('Quotes/Random/SecureList', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
        ]);
    }
}
