<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ApiTestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Api/Test', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
            'randomInspirationalImage' => "image"
        ]);
    }
}
