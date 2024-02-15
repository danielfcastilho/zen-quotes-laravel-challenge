<?php

namespace App\Services\Api;

abstract class ApiStrategyInterface
{
    /**
     * Fetch data using the strategy's specific API call.
     *
     * @return mixed
     */
    abstract public function fetchData();
}