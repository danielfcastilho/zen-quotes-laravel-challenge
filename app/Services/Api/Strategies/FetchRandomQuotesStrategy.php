<?php

namespace App\Services\Api\Strategies;

use App\Services\Api\ApiStrategyInterface;
use Illuminate\Support\Facades\Http;

class FetchRandomQuotesStrategy extends ApiStrategyInterface
{
    protected $apiUrl = 'https://zenquotes.io/api/quotes';

    public function fetchData()
    {
        $response = Http::get($this->apiUrl);
        return array_slice($response->json(), 0, 5);
    }
}
