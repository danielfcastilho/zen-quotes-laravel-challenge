<?php

namespace App\Services\Api;

class ApiResponse
{
    public $data;
    public $fromCache;

    public function __construct($data = [], bool $fromCache = false)
    {
        $this->data = $data;
        $this->fromCache = $fromCache;
    }
}