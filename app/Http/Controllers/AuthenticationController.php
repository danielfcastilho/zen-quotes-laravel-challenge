<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticationController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Login', [
            'authenticatedUsers' => session()->get('authenticatedUsers', []),
            'username' => $request->get('username', null)
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        $authenticatedUsers = session()->get('authenticatedUsers', []);
        if (!in_array($user->username, $authenticatedUsers)) {
            $authenticatedUsers[] = $user->username;
        }
        session()->put('authenticatedUsers', $authenticatedUsers);

        $apiToken = $user->createToken('apiToken')->plainTextToken;

        session()->put('apiToken', $apiToken);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $authenticatedUsers = session()->get('authenticatedUsers', []);

        if (($key = array_search($request->user()->username, $authenticatedUsers)) !== false) {
            unset($authenticatedUsers[$key]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->put('authenticatedUsers', $authenticatedUsers);

        return redirect('/');
    }
}
