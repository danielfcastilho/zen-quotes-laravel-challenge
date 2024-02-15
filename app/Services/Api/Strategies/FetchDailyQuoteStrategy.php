<?php

namespace App\Services\Api\Strategies;

use App\Services\Api\ApiStrategyAbstract;
use Illuminate\Support\Facades\Http;

class FetchDailyQuoteStrategy extends ApiStrategyAbstract
{
    protected $apiUrl = 'https://zenquotes.io/api/today';

    public function fetchData()
    {
        $response = Http::get($this->apiUrl);

        $data = $response->json();

        $this->validateResponse($data);

        return $data;
    }

    protected function rules(): array
    {
        return [
            '0.q' => 'required|string',
            '0.a' => 'required|string',
        ];
    }
}
