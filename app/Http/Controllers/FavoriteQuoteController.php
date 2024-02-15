<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteQuoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Quotes/Favorites/List', [
            'quotes' => QuoteResource::collection($user->favoriteQuotes()->get()),
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
            'users' => UserResource::collection($users),
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
