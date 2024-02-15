<?php

namespace App\Enums;

enum StrategyType: string
{
    case DailyQuote = 'daily_quote';
    case InspirationalImage = 'inspirational_image';
    case RandomQuotes = 'random_quotes';
    case SecureQuotes = 'secure_quotes';
}