<?php

namespace App\Http\Controllers;

use App\Enums\StrategyType;
use App\Http\Resources\QuoteResource;
use App\Services\Api\ApiService;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RandomQuoteController extends Controller
{
    public function __construct(
        protected ApiService $apiService,
        protected QuoteService $quoteService
    ) {
    }

    public function index(Request $request, $new = false)
    {
        $randomQuotesResponse = $this->apiService->fetchData(StrategyType::RandomQuotes, $new);

        $randomQuotes = $this->quoteService->findOrCreateMany($randomQuotesResponse->data, $randomQuotesResponse->fromCache);

        $randomQuotesCollection = QuoteResource::collection($randomQuotes);

        if ($request->is('api/*')) {
            return response()->json([
                'quotes' => $randomQuotesCollection,
            ]);
        }

        return Inertia::render('Quotes/Random/List', [
            'quotes' => $randomQuotesCollection,
        ]);
    }

    public function secureIndex(Request $request, $new = false)
    {
        $secureQuotesResponse = $this->apiService->fetchData(StrategyType::SecureQuotes, $new);

        $secureQuotes = $this->quoteService->findOrCreateMany($secureQuotesResponse->data, $secureQuotesResponse->fromCache);

        $secureQuotesCollection = QuoteResource::collection($secureQuotes);

        if ($request->is('api/*')) {
            return response()->json([
                'quotes' => $secureQuotesCollection,
            ]);
        }

        return Inertia::render('Quotes/Random/SecureList', [
            'quotes' => $secureQuotesCollection,
        ]);
    }
}
