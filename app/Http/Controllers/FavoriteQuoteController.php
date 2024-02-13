<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function report(Request $request)
    {
        $loggedInUser = $request->user();
        $users = User::with('favoriteQuotes')->get();
        $loggedInUserIndex = $users->search(function ($user) use ($loggedInUser) {
            return $user->id === $loggedInUser->id;
        });
        $loggedInUser = $users->pull($loggedInUserIndex);
        $users->prepend($loggedInUser);

        return Inertia::render('Quotes/Favorites/Report', [
            'users' => $users->toArray(),
        ]);
    }

    public function update(Request $request)
    {
        if ($request->input('action') === 'add') {
            $request->user()->favoriteQuotes()->attach($request->input('quote_id'));
        } else if ($request->input('action') === 'remove') {
            $request->user()->favoriteQuotes()->detach($request->input('quote_id'));
        }
    }
}
