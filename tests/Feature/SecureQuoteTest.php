<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SecureQuoteTest extends TestCase
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
     * A web page with URI of “/secure-quotes” that shows 10 random quotes.
     */
    public function test_a_web_page_with_uri_of_secure_quotes_that_shows_10_random_quotes()
    {
        $response = $this->actingAs($this->user)->get('/secure-quotes');

        $response->assertInertia(fn ($page) =>
        $page->has('quotes')
            ->where(
                'quotes',
                function ($quotes) {
                    $this->assertCount(10, $quotes);
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
        $this->actingAs($this->user)->get('/secure_quotes');

        $cachedQuotes = Cache::get('secure_quotes');

        $response = $this->actingAs($this->user)->get('/secure_quotes');

        $response->assertInertia(
            function ($page) use ($cachedQuotes) {
                $page->has('quotes')
                    ->where('quotes', function ($quotes) use ($cachedQuotes) {
                        $expectedQuotes = array_map(function ($quote) {
                            return $quote['quote_text'];
                        }, $cachedQuotes['quotes']);

                        $actualQuotes = array_map(function ($quote) {
                            return $quote['quote_text'];
                        }, $quotes->toArray());

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
        $this->actingAs($this->user)->get('/secure-quotes');

        $response = $this->actingAs($this->user)->get('/secure-quotes');

        $response->assertInertia(
            fn ($page) => $page->has('quotes')
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
     * There should be a button to force a reload of a list of 10 random quotes with a “new” parameter (e.g., /secure-quotes/new).
     */
    public function test_there_should_be_a_button_to_force_a_reload_of_a_list_of_10_random_quotes_with_a_new_parameter()
    {
        $this->get('/secure-quotes');

        $response = $this->get('/secure-quotes/new');

        $response->assertInertia(
            fn ($page) => $page->has('quotes')
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
     * The page is accessible to authenticated/logged in users only.
     */
    public function test_the_page_is_accessible_to_unauthenticated_users_only()
    {
        $response = $this->actingAs($this->user)->get('/secure-quotes');

        $response->assertOk();
    }

    /**
     * The page redirects to “/quotes” for unauthenticated users.
     */
    public function test_the_page_redirects_to_quotes_for_unauthenticated_users()
    {
        $response = $this->get('/secure-quotes');

        $response->assertRedirect('/quotes');
    }
}
