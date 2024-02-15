<?php

namespace App\Services\Api\Strategies;

use App\Services\Api\ApiStrategyAbstract;
use Illuminate\Support\Facades\Http;

class FetchSecureQuotesStrategy extends ApiStrategyAbstract
{
    protected $apiUrl = 'https://zenquotes.io/api/quotes';

    public function fetchData()
    {
        $response = Http::get($this->apiUrl);

        $data = $response->json();

        $this->validateResponse($data);

        return array_slice($data, 0, 10);
    }

    protected function rules(): array
    {
        return [
            '*' => 'required|array',
            '*.q' => 'required|string',
            '*.a' => 'required|string',
        ];
    }
}
