<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('secure-quotes') || $request->is('secure-quotes/*') || $request->is('favorite-quotes')) {
            return route('quotes');
        }

        return $request->expectsJson() ? null : route('login');
    }
}
