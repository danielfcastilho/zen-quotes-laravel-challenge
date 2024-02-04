<?php

namespace Tests;

trait AuthenticationHelper
{
    /**
     * Create a user, log them in, and return a token.
     *
     * @param array $userAttributes Attributes to override default user attributes.
     * @return string|null
     */
    protected function registerAndLoginUser(array $userAttributes = []): string|null
    {
        $this->registerUser($userAttributes);

        $response = $this->loginUser(
            $userAttributes['email'] ?? 'test@example.com',
            $userAttributes['password'] ?? 'password'
        );

        return $response->json('token') ?? null;
    }

    protected function registerUser(array $userAttributes = [])
    {
        return $this->post('/register', [
            'name' => $userAttributes['name'] ?? 'Test User',
            'username' => $userAttributes['email'] ?? 'test@example.com',
            'password' => $userAttributes['password'] ?? 'password',
        ]);
    }

    protected function loginUser(string $email, string $password)
    {
        return $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);
    }
}
