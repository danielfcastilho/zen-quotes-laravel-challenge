<?php

namespace App\Http\Controllers;

use App\Enums\StrategyType;
use App\Http\Resources\QuoteResource;
use App\Services\Api\ApiService;
use App\Services\QuoteService;
use Inertia\Inertia;

class DailyQuoteController extends Controller
{
    public function __construct(
        protected ApiService $apiService,
        protected QuoteService $quoteService
    ) {
    }

    public function show($new = false)
    {
        $dailyQuoteResponse = $this->apiService->fetchData(StrategyType::DailyQuote, $new);

        $imageResponse = $this->apiService->fetchData(StrategyType::InspirationalImage, $new);

        $quote = $this->quoteService->findOrCreate($dailyQuoteResponse->data, $dailyQuoteResponse->fromCache);

        return Inertia::render('Quotes/Daily/Show', [
            'quote' => new QuoteResource($quote),
            'randomInspirationalImagePath' => $imageResponse->data
        ]);
    }
}
