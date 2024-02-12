<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RandomQuoteTest extends TestCase
{
    use RefreshDatabase;

    protected $randomQuotesStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->randomQuotesStub = [
            [
                "q" => "To improve is to change, so to be perfect is to change often.",
                "a" => "Winston Churchill",
                "c" => "61",
                "h" => "<blockquote>&ldquo;To improve is to change, so to be perfect is to change often.&rdquo; &mdash; <footer>Winston Churchill</footer></blockquote>"
            ],
            [
                "q" => "Desire for the fruits of work must never be your motive in working.",
                "a" => "Bhagavad Gita",
                "c" => "67",
                "h" => "<blockquote>&ldquo;Desire for the fruits of work must never be your motive in working.&rdquo; &mdash; <footer>Bhagavad Gita</footer></blockquote>"
            ],
            [
                "q" => "Still your waters.",
                "a" => "Josh Waitzkin",
                "c" => "18",
                "h" => "<blockquote>&ldquo;Still your waters.&rdquo; &mdash; <footer>Josh Waitzkin</footer></blockquote>"
            ],
            [
                "q" => "Night is a more quiet time to work. It aids thought.",
                "a" => "Alexander Graham Bell",
                "c" => "52",
                "h" => "<blockquote>&ldquo;Night is a more quiet time to work. It aids thought.&rdquo; &mdash; <footer>Alexander Graham Bell</footer></blockquote>"
            ],
            [
                "q" => "Have the fearless attitude of a hero and the loving heart of a child.",
                "a" => "Soyen Shaku",
                "c" => "69",
                "h" => "<blockquote>&ldquo;Have the fearless attitude of a hero and the loving heart of a child.&rdquo; &mdash; <footer>Soyen Shaku</footer></blockquote>"
            ],
        ];

        Http::fake([
            'zenquotes.io/api/quotes' => Http::response($this->randomQuotesStub, 200),
        ]);
    }

    /**
     * A web page with URI of “/quotes” that shows 5 random quotes.
     */
    public function test_a_web_page_uri_of_quotes_that_shows_5_random_quotes()
    {
        $response = $this->get('/quotes');

        $response->assertInertia(fn ($page) =>
        $page->has('quotes')
            ->where(
                'quotes',
                function ($quotes) {
                    foreach ($quotes as $quote) {
                        if (empty($quote['quote_text']) || empty($quote['author_name'])) {
                            return false;
                        }
                    }
                    return true;
                }
            ));
    }

    /**
     * The web page should display cached information, if available, by default.
     */
    public function test_the_web_page_should_display_cached_information_if_available_by_default()
    {
        $this->get('/quotes');

        $cachedQuotes = Cache::get('random_quotes');

        $response = $this->get('/quotes');

        $response->assertInertia(
            function ($page) use ($cachedQuotes) {
                $page->has('quotes')
                    ->where('quotes', function ($quotes) use ($cachedQuotes) {
                        $expectedQuotes = array_map(function ($quote) {
                            return $quote['quote_text'];
                        }, $cachedQuotes['quotes']);

                        $actualQuotes = array_map(function ($quote) {
                            return $quote['quote_text'];
                        }, $quotes);

                        sort($expectedQuotes);
                        sort($actualQuotes);

                        return $expectedQuotes === $actualQuotes;
                    });
            }
        );
    }

    /**
     * If cache was used, the quote should be prefixed with an appropriate icon or “[cached]” keyword/tag.
     */
    public function test_if_cache_was_used_the_quote_should_be_prefixed_with_an_appropriate_icon_or_cached_keyword_tag()
    {
        $this->get('/quotes');

        $response = $this->get('/quotes');

        $response->assertInertia(
            fn ($page) => $page->has('quote')
                ->where('quotes', function ($quotes) {
                    foreach ($quotes as $quote) {
                        if (!str_contains($quote['quote_text'], '[cached]')) {
                            return false;
                        }
                    }
                    return true;
                })
        );
    }

    /**
     * There should be a button to force a reload of the “quote of the day” with a “new” parameter (e.g., /today/new).
     */
    public function test_there_should_be_a_button_to_force_a_reload_of_the_quote_of_the_day_with_a_new_parameter()
    {
        $this->get('/quotes');

        $response = $this->get('/quotes/new');

        $response->assertInertia(
            fn ($page) => $page->has('quote')
                ->where('quotes', function ($quotes) {
                    foreach ($quotes as $quote) {
                        if (str_contains($quote['quote_text'], '[cached]')) {
                            return false;
                        }
                    }
                    return true;
                })
        );
    }

    /**
     * The page is accessible to unauthenticated users.
     */
    public function test_the_page_is_accessible_to_unauthenticated_users()
    {
        $response = $this->get('/quotes');

        $response->assertOk();
    }

    /**
     * The page redirects to “/secure-quotes” for authenticated/logged in users.
     */
    public function test_the_page_redirects_to_secure_quotes_for_authenticated_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/quotes');

        $response->assertRedirect('/secure-quotes');
    }
}
