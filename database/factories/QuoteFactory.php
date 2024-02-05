<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quoteText = fake()->sentence(6, true);

        return [
            'quote_text' => $quoteText,
            'author_name' => fake()->name(),
            'character_count' => strlen($quoteText),
            'html_representation' => "<blockquote>&ldquo;{$quoteText}&rdquo; &mdash; <footer>" . fake()->name() . "</footer></blockquote>",
        ];
    }
}
