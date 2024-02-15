<?php

namespace App\Services\Api;

class ApiResponse
{
    public $data;
    public $fromCache;

    public function __construct($data, bool $fromCache)
    {
        $this->data = $data;
        $this->fromCache = $fromCache;
    }
}