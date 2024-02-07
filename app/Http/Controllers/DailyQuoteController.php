<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DailyQuoteController extends Controller
{
    public function show()
    {
        $apiUrl = "https://zenquotes.io/api/image";

        $imageContent = file_get_contents($apiUrl);

        $filename = 'inspirational_images/' . uniqid() . '.jpg';

        Storage::disk('public')->put($filename, $imageContent);

        return Inertia::render('Quotes/Daily/Show', [
            'quote' => ['quote_text' => "Yesterday is gone. Tomorrow has not yet come. We have only todayYesterday is gone. Tomorrow has not yet come. We have only today [cached]", 'author_name' => "Robert D'niro"],
            'randomInspirationalImagePath' => '/storage/' . $filename
        ]);
    }
}
