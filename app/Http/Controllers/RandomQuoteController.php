<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class RandomQuoteController extends Controller
{
    public function index(Request $request, $new = null)
    {
        $apiUrl = "https://zenquotes.io/api/";
        $cacheKey = 'random_quotes';

        $cachedData = Cache::get($cacheKey);

        if ($cachedData && !$new) {
            if ($request->wantsJson()) {
                return response()->json([
                    'quotes' => $cachedData['quotes'],
                ]);
            }

            return Inertia::render('Quotes/Random/List', [
                'quotes' => $cachedData['quotes'],
            ]);
        }

        $response = Http::get($apiUrl . 'quotes');

        $quotesResponse = $response->json();

        $quotes = new Collection();
        foreach ($quotesResponse as $key => $quoteResponse) {
            if ($key === 5) {
                break;
            }
            $quote = Quote::where([
                'quote_text' => $quoteResponse['q'],
                'author_name' => $quoteResponse['a']
            ])->first();

            if (!$quote) {
                $quote = new Quote();
                $quote->quote_text = $quoteResponse['q'];
                $quote->author_name = $quoteResponse['a'];
                $quote->save();
            }

            $quotes->push($quote);
        }

        $randomQuotes = $quotes->toArray();

        Cache::put($cacheKey, [
            'quotes' => $quotes->map(function ($quote) {
                $quote->quote_text = '[cached]' . $quote->quote_text;
                return $quote;
            })->toArray(),
        ], 30);

        if ($request->wantsJson()) {
            return response()->json([
                'quotes' => $randomQuotes,
            ]);
        }

        return Inertia::render('Quotes/Random/List', [
            'quotes' => $randomQuotes,
        ]);
    }

    public function secureIndex(Request $request, $new = null)
    {
        $apiUrl = "https://zenquotes.io/api/";
        $cacheKey = 'secure_quotes';

        $cachedData = Cache::get($cacheKey);

        $user = $request->user();

        if ($cachedData && !$new) {
            return Inertia::render('Quotes/Random/SecureList', [
                'quotes' => $cachedData['quotes'],
                'favorites' => $user->favoriteQuotes()->pluck('quotes.id')->toArray(),
            ]);
        }

        $response = Http::get($apiUrl . 'quotes');

        $quotesResponse = $response->json();

        $quotes = new Collection();
        foreach ($quotesResponse as $key => $quoteResponse) {
            if ($key === 10) {
                break;
            }
            $quote = Quote::where([
                'quote_text' => $quoteResponse['q'],
                'author_name' => $quoteResponse['a']
            ])->first();

            if (!$quote) {
                $quote = new Quote();
                $quote->quote_text = $quoteResponse['q'];
                $quote->author_name = $quoteResponse['a'];
                $quote->save();
            }

            $quotes->push($quote);
        }

        $secureQuotes = $quotes->toArray();

        Cache::put($cacheKey, [
            'quotes' => $quotes->map(function ($quote) {
                $quote->quote_text = '[cached]' . $quote->quote_text;
                return $quote;
            })->toArray(),
        ], 30);

        if ($request->wantsJson()) {
            return response()->json([
                'quotes' => $secureQuotes,
            ]);
        }

        return Inertia::render('Quotes/Random/SecureList', [
            'quotes' => $secureQuotes,
            'favorites' => $user->favoriteQuotes()->pluck('quotes.id')->toArray(),
        ]);
    }
}
