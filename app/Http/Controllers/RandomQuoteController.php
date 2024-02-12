<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class RandomQuoteController extends Controller
{
    public function index($new = null)
    { {
            $apiUrl = "https://zenquotes.io/api/";
            $cacheKey = 'random_quotes';

            $cachedData = Cache::get($cacheKey);

            if ($cachedData && !$new) {
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

            return Inertia::render('Quotes/Random/List', [
                'quotes' => $randomQuotes,
            ]);
        }
    }

    public function secureIndex($new = null)
    {
        return Inertia::render('Quotes/Random/SecureList', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
        ]);
    }
}
