<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RandomQuoteAPITest extends TestCase
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
     * REST API GET endpoint with URI of “/api/quotes” for Feature: Five Random Quotes.
     */
    public function test_rest_api_get_endpoint_with_uri_of_api_quotes_for_feature_five_random_quotes()
    {
        $response = $this->getJson('/api/quotes');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        $this->assertCount(5, $quotes);
    }

    /**
     * The API should return cached information, if available, by default.
     */
    public function test_the_api_should_return_cached_information_if_available_by_default()
    {
        $this->get('/api/quotes');

        $cachedQuotes = Cache::get('random_quotes');

        $response = $this->getJson('/api/quotes');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        $expectedQuotes = array_map(function ($quote) {
            return $quote['quote_text'];
        }, $cachedQuotes['quotes']);

        $actualQuotes = array_map(function ($quote) {
            return $quote['quote_text'];
        }, $quotes);

        sort($expectedQuotes);
        sort($actualQuotes);

        $this->assertTrue($expectedQuotes === $actualQuotes);
    }

    /**
     * If cache was used, each of the quotes should be prefixed with “[cached] ” keyword/tag.
     */
    public function test_if_cache_was_used_each_of_the_quotes_should_be_prefixed_with_cached_keyword_tag()
    {
        $this->getJson('/api/quotes');

        $response = $this->getJson('/api/quotes');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        foreach ($quotes as $quote) {
            $this->assertTrue(str_contains($quote['quote_text'], '[cached]'));
        }
    }

    /**
     * The API shares cache with the Feature: Five Random Quotes.
     */
    public function test_the_api_shares_cache_with_the_feature_five_random_quotes()
    {
        $this->get('/quotes');

        $cachedQuotes = Cache::get('random_quotes');

        $response = $this->getJson('/api/quotes');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        $expectedQuotes = array_map(function ($quote) {
            return $quote['quote_text'];
        }, $cachedQuotes['quotes']);

        $actualQuotes = array_map(function ($quote) {
            return $quote['quote_text'];
        }, $quotes);

        sort($expectedQuotes);
        sort($actualQuotes);

        $this->assertTrue($expectedQuotes === $actualQuotes);
    }

    /**
     * The API can be forced to fetch and return fresh set of results (and update the cache) with a “new” parameter (e.g., “/api/quotes/new”).
     */
    public function test_the_api_can_be_forced_to_fetch_and_return_fresh_set_of_results_with_a_new_parameter()
    {
        $this->getJson('/api/quotes');

        $response = $this->getJson('/api/quotes/new');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        foreach ($quotes as $quote) {
            $this->assertFalse(str_contains($quote['quote_text'], '[cached]'));
        }
    }

    /**
     * The page is accessible to unauthenticated users.
     */
    public function test_the_page_is_accessible_to_unauthenticated_users()
    {
        $response = $this->getJson('/api/quotes');

        $response->assertJsonCount(5, 'quotes');
    }

    /**
     * The page redirects to “/secure-quotes” for authenticated/logged in users.
     */
    public function test_the_page_redirects_to_secure_quotes_for_authenticated_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/quotes');

        $response->assertJsonCount(5, 'quotes');
    }
}
