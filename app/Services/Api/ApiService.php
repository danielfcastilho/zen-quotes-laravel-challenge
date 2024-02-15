<?php

namespace App\Services\Api;

use App\Enums\StrategyType;
use App\Services\Api\ApiStrategyFactory;
use Illuminate\Support\Facades\Cache;

class ApiService
{
    public function fetchData(StrategyType $strategyType, bool $forceNew = false, int $cacheDuration = 30): ApiResponse
    {
        $strategy = ApiStrategyFactory::make($strategyType);

        if (!$forceNew && Cache::has($strategyType->value)) {
            $cachedData = Cache::get($strategyType->value);
            return new ApiResponse($cachedData, true);
        }

        $data = $strategy->fetchData();

        Cache::put($strategyType->value, $data, $cacheDuration);

        return new ApiResponse($data, false);
    }
}
