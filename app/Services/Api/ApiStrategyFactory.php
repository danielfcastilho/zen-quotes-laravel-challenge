<?php

namespace App\Services\Api;

use App\Enums\StrategyType;
use App\Services\Api\Strategies\FetchDailyQuoteStrategy;
use App\Services\Api\Strategies\FetchInspirationalImageStrategy;
use App\Services\Api\Strategies\FetchRandomQuotesStrategy;
use App\Services\Api\Strategies\FetchSecureQuotesStrategy;

class ApiStrategyFactory
{
    /**
     * Create an instance of a strategy based on the type.
     *
     * @param StrategyType $type The type of strategy.
     * @return ApiStrategyInterface An instance of a class implementing ApiStrategyInterface.
     */
    public static function make(StrategyType $type): ApiStrategyInterface
    {
        return match ($type) {
            StrategyType::DailyQuote => new FetchDailyQuoteStrategy(),
            StrategyType::InspirationalImage => new FetchInspirationalImageStrategy(),
            StrategyType::RandomQuotes => new FetchRandomQuotesStrategy(),
            StrategyType::SecureQuotes => new FetchSecureQuotesStrategy(),
        };
    }
}