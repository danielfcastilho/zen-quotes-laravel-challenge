<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * The plainText password being used by the factory.
     */
    protected static string $defaultPassword = 'password';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make(static::$defaultPassword)
        ];
    }

    /**
     * Get the default password used by the factory.
     *
     * @return string
     */
    public static function defaultPassword(): string
    {
        return static::$defaultPassword;
    }
}
