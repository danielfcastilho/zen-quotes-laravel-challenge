<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DailyQuoteController extends Controller
{
    public function show($new = null)
    {
        $apiUrl = "https://zenquotes.io/api/";
        $cacheKey = 'daily_quote';

        $cachedData = Cache::get($cacheKey);

        if ($cachedData && !$new) {
            return Inertia::render('Quotes/Daily/Show', [
                'quote' => $cachedData['quote'],
                'isFavorite' => Auth::user() && DB::table('favorite_quotes')
                    ->where('user_id', Auth::user()->id)
                    ->where('quote_id', $cachedData['quote']['id'])
                    ->exists(),
                'randomInspirationalImagePath' => $cachedData['randomInspirationalImagePath']
            ]);
        }

        $imageContent = file_get_contents($apiUrl . 'image');

        $imageName = 'inspirational_images/' . uniqid() . '.jpg';
        Storage::disk('public')->put($imageName, $imageContent);
        $imagePath = '/storage/' . $imageName;

        $response = Http::get($apiUrl . 'today');

        $todayResponse = $response->json();

        if (!$quote = Quote::where([
            'quote_text' => $todayResponse[0]['q'],
            'author_name' => $todayResponse[0]['a']
        ])->first()) {
            $quote = new Quote();
            $quote->quote_text = $todayResponse[0]['q'];
            $quote->author_name = $todayResponse[0]['a'];
            $quote->save();
        }

        Cache::put($cacheKey, [
            'quote' => [
                'id' => $quote->id,
                'quote_text' => '[cached] ' . $todayResponse[0]['q'],
                'author_name' => $todayResponse[0]['a']
            ],
            'randomInspirationalImagePath' => $imagePath,
        ], 30);

        return Inertia::render('Quotes/Daily/Show', [
            'quote' => [
                'id' => $quote->id,
                'quote_text' => $todayResponse[0]['q'],
                'author_name' => $todayResponse[0]['a']
            ],
            'isFavorite' => Auth::guard('web')->check() &&
                DB::table('favorite_quotes')
                ->where('user_id', Auth::user()->id)
                ->where('quote_id', $quote->id)
                ->exists(),
            'randomInspirationalImagePath' => $imagePath
        ]);
    }
}
