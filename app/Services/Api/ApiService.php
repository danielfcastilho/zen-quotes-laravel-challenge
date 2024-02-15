<?php

namespace App\Services\Api;

use App\Enums\StrategyType;
use App\Services\Api\ApiStrategyFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiService
{
    public function fetchData(StrategyType $strategyType, bool $forceNew = false, int $cacheDuration = 30): ApiResponse
    {
        $strategy = ApiStrategyFactory::make($strategyType);

        if (!$forceNew && Cache::has($strategyType->value)) {
            $cachedData = Cache::get($strategyType->value);
            return new ApiResponse($cachedData, true);
        }

        try {
            $data = $strategy->fetchData();
        } catch (\Exception $e) {
            Log::error('API request returned with an error: ' . $e->getMessage());
            return new ApiResponse();
        }

        Cache::put($strategyType->value, $data, $cacheDuration);

        return new ApiResponse($data, false);
    }
}
