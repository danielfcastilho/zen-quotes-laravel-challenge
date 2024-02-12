<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteQuoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Quotes/Favorites/List', [
            'quotes' => $user->favoriteQuotes()->get()->toArray(),
        ]);
    }

    public function report()
    {
        return Inertia::render('Quotes/Favorites/Report', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
        ]);
    }

    public function store(Request $request)
    {
        $request->user()->favoriteQuotes()->attach($request->input('quote_id'));
    }

    public function destroy(Request $request)
    {
        $request->user()->favoriteQuotes()->detach($request->input('quote_id'));
    }
}
