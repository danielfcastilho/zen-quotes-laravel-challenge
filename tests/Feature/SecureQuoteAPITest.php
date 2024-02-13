<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SecureQuoteAPITest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $secureQuotesStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secureQuotesStub = [
            [
                "q" => "Just living is not enough... one must have sunshine, freedom, and a little flower. ",
                "a" => "Hans Christian Andersen",
                "c" => "83",
                "h" => "<blockquote>&ldquo;Just living is not enough... one must have sunshine, freedom, and a little flower. &rdquo; &mdash; <footer>Hans Christian Andersen</footer></blockquote>"
            ],
            [
                "q" => "Imagination is everything. It is the preview of life's coming attractions.",
                "a" => "Albert Einstein",
                "c" => "74",
                "h" => "<blockquote>&ldquo;Imagination is everything. It is the preview of life's coming attractions.&rdquo; &mdash; <footer>Albert Einstein</footer></blockquote>"
            ],
            [
                "q" => "All problems are illusions of the mind.",
                "a" => "Eckhart Tolle",
                "c" => "39",
                "h" => "<blockquote>&ldquo;All problems are illusions of the mind.&rdquo; &mdash; <footer>Eckhart Tolle</footer></blockquote>"
            ],
            [
                "q" => "The sculptor produces the beautiful statue by chipping away such parts of the marble block as are not needed - it is a process of elimination.",
                "a" => "Elbert Hubbard",
                "c" => "142",
                "h" => "<blockquote>&ldquo;The sculptor produces the beautiful statue by chipping away such parts of the marble block as are not needed - it is a process of elimination.&rdquo; &mdash; <footer>Elbert Hubbard</footer></blockquote>"
            ],
            [
                "q" => "You have the potential for greatness.",
                "a" => "Steve Harvey",
                "c" => "37",
                "h" => "<blockquote>&ldquo;You have the potential for greatness.&rdquo; &mdash; <footer>Steve Harvey</footer></blockquote>"
            ],
            [
                "q" => "Obstacles don't block the path, they are the path.",
                "a" => "Zen Proverb",
                "c" => "50",
                "h" => "<blockquote>&ldquo;Obstacles don't block the path, they are the path.&rdquo; &mdash; <footer>Zen Proverb</footer></blockquote>"
            ],
            [
                "q" => "Death smiles at us all. All we can do is smile back.",
                "a" => "Marcus Aurelius",
                "c" => "52",
                "h" => "<blockquote>&ldquo;Death smiles at us all. All we can do is smile back.&rdquo; &mdash; <footer>Marcus Aurelius</footer></blockquote>"
            ],
            [
                "q" => "Conquer the devils with a little thing called love.",
                "a" => "Bob Marley",
                "c" => "51",
                "h" => "<blockquote>&ldquo;Conquer the devils with a little thing called love.&rdquo; &mdash; <footer>Bob Marley</footer></blockquote>"
            ],
            [
                "q" => "Rather than love, than money, than fame, give me truth.",
                "a" => "Henry David Thoreau",
                "c" => "55",
                "h" => "<blockquote>&ldquo;Rather than love, than money, than fame, give me truth.&rdquo; &mdash; <footer>Henry David Thoreau</footer></blockquote>"
            ],
            [
                "q" => "Anger exceeding limits causes fear and excessive kindness eliminates respect.",
                "a" => "Euripides",
                "c" => "77",
                "h" => "<blockquote>&ldquo;Anger exceeding limits causes fear and excessive kindness eliminates respect.&rdquo; &mdash; <footer>Euripides</footer></blockquote>"
            ],
        ];

        Http::fake([
            'zenquotes.io/api/quotes' => Http::response($this->secureQuotesStub, 200),
        ]);

        $this->user = User::factory()->create();
    }

    /**
     * REST API GET and POST endpoint with URI of “/api/secure-quotes” for Feature: Ten Secure Quotes.
     */
    public function test_api_get_and_post_endpoint_with_uri_of_api_secure_quote_for_feature_ten_secure_quotes()
    {
        $response = $this->actingAs($this->user)->getJson('/api/secure-quotes');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        $this->assertCount(10, $quotes);
    }

    /**
     * The API should return cached information, if available, by default.
     */
    public function test_the_api_should_display_cached_information_if_available_by_default()
    {
        $this->actingAs($this->user)->get('/api/secure-quotes');

        $cachedQuotes = Cache::get('secure_quotes');

        $response = $this->actingAs($this->user)->getJson('/api/secure-quotes');

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
     * If cache was used, the quote should be prefixed with an appropriate icon or “[cached]” keyword/tag.
     */
    public function test_if_cache_was_used_the_quote_should_be_prefixed_with_an_appropriate_icon_or_cached_keyword_tag()
    {
        $this->actingAs($this->user)->getJson('/api/secure-quotes');

        $response = $this->actingAs($this->user)->getJson('/api/secure-quotes');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        foreach ($quotes as $quote) {
            $this->assertTrue(str_contains($quote['quote_text'], '[cached]'));
        }
    }

    /**
     * The API shares cache with the Feature: Ten Secure Quotes.
     */
    public function test_the_api_shares_cache_with_the_feature_ten_secure_quotes()
    {
        $this->actingAs($this->user)->get('/secure-quotes');

        $cachedQuotes = Cache::get('secure_quotes');

        $response = $this->actingAs($this->user)->getJson('/api/secure-quotes');
        
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
     * The API can be forced to fetch and return fresh set of results (and update the cache) with a “new” parameter (e.g., “/api/secure-quotes/new”).
     */
    public function test_the_api_can_be_forced_to_fetch_and_return_fresh_set_of_results_with_a_new_parameter()
    {
        $this->actingAs($this->user)->getJson('/api/secure-quotes');

        $response = $this->actingAs($this->user)->getJson('/api/secure-quotes/new');

        $responseData = json_decode($response->content(), true);

        $quotes = $responseData['quotes'];

        foreach ($quotes as $quote) {
            $this->assertFalse(str_contains($quote['quote_text'], '[cached]'));
        }
    }

    /**
     * The API endpoint is secured with API login token.
     */
    public function test_the_api_endpoint_is_secured_with_api_login_token()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $token = session()->get('apiToken', null);

        $response = $this->withHeader('Authorization', "Bearer " . $token)->getJson('/api/secure-quotes');

        $response->assertOk();
        $response->assertJsonCount(10, 'quotes');
    }

    /**
     * The page is accessible to authenticated/logged in users only.
     */
    public function test_the_page_is_accessible_to_authenticated_users_only()
    {
        $response = $this->actingAs($this->user)->getJson('/api/secure-quotes');

        $response->assertOk();
        $response->assertJsonCount(10, 'quotes');

        $this->refreshApplication();

        $response = $this->getJson('/api/secure-quotes');

        $response->assertUnauthorized();
        $response->assertExactJson([]);
    }

    /**
     * The page returns empty JSON for unauthenticated users.
     */
    public function test_the_return_empty_json_for_unauthenticated_users()
    {
        $response = $this->getJson('/api/secure-quotes');

        $response->assertUnauthorized();
        $response->assertExactJson([]);
    }
}
