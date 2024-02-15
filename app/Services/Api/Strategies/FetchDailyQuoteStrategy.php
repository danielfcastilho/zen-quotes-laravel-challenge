<?php

namespace App\Services\Api\Strategies;

use App\Services\Api\ApiStrategyInterface;
use Illuminate\Support\Facades\Http;

class FetchDailyQuoteStrategy extends ApiStrategyInterface
{
    protected $apiUrl = 'https://zenquotes.io/api/today';

    public function fetchData()
    {
        $response = Http::get($this->apiUrl);
        return $response->json();
    }
}
