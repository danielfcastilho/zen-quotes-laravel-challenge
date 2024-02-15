<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateFavoriteRequest;
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

    public function update(UpdateFavoriteRequest $request)
    {
        $user = $request->user();

        $quoteId = $request->validated('quote_id');
        $action = $request->validated('action');

        switch ($action) {
            case 'add':
                if (!$user->favoriteQuotes()->where('quote_id', $quoteId)->exists()) {
                    $user->favoriteQuotes()->attach($quoteId);
                } else {
                    return response()->json([], 400);
                }
                break;
            case 'remove':
                if ($user->favoriteQuotes()->where('quote_id', $quoteId)->exists()) {
                    $user->favoriteQuotes()->detach($quoteId);
                } else {
                    return response()->json([], 400);
                }
                break;
            default:
                return response()->json([], 400);
        }
        return response()->json([], 200);
    }
}
