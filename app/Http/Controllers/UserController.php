<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'username' => $request->validated('username'),
            'password' => Hash::make($request->validated('password')),
        ]);

        Auth::login($user);

        $authenticatedUsers = session()->get('authenticatedUsers', []);
        if (!in_array($user->username, $authenticatedUsers)) {
            $authenticatedUsers[] = $user->username;
        }
        session()->put('authenticatedUsers', $authenticatedUsers);

        $apiToken = $user->createToken('apiToken')->plainTextToken;

        session()->put('apiToken', $apiToken);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $oldUsername = $request->user()->username;

        $authenticatedUsers = session()->get('authenticatedUsers', []);
        foreach ($authenticatedUsers as $key => $authenticatedUser) {
            if ($oldUsername === $authenticatedUser) {
                $authenticatedUsers[$key] = $request->validated('username');
            }
        }
        session()->put('authenticatedUsers', $authenticatedUsers);

        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit');
    }
}
