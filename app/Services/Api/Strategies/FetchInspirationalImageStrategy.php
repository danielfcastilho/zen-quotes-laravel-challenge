<?php

namespace App\Services\Api\Strategies;

use App\Services\Api\ApiStrategyInterface;
use Illuminate\Support\Facades\Storage;

class FetchInspirationalImageStrategy extends ApiStrategyInterface
{
    protected $apiUrl = 'https://zenquotes.io/api/image';

    public function fetchData()
    {
        $imageContent = file_get_contents($this->apiUrl);

        $imageName = 'inspirational_images/' . uniqid() . '.jpg';
        Storage::disk('public')->put($imageName, $imageContent);
        $imagePath = '/storage/' . $imageName;

        return $imagePath;
    }
}
