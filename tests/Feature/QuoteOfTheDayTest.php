<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuoteOfTheDayTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $dailyQuoteStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dailyQuoteStub = [
            'q' => 'The greatest glory in living lies not in never falling, but in rising every time we fall.',
            'a' => 'Nelson Mandela',
            'h' => '<blockquote>&ldquo;The greatest glory in living lies not in never falling, but in rising every time we fall.&rdquo; &mdash; <footer>Nelson Mandela</footer></blockquote>',
        ];

        Http::fake([
            'zenquotes.io/api/today' => Http::response([
                $this->dailyQuoteStub
            ], 200),
            'zenquotes.io/api/image' => Http::response([], 200),
        ]);

        Storage::fake('public');

        $this->user = User::factory()->create();
    }

    /**
     * A web page with URI of “/today” that shows “quote of the day”.
     */
    public function test_a_web_page_uri_of_today_that_shows_quote_of_the_day()
    {
        $response = $this->actingAs($this->user)->get('/today');

        $response->assertInertia(fn ($page) =>
        $page->has('quote')
            ->where(
                'quote',
                function ($quote) {
                    return !empty($quote['quote_text']) && !empty($quote['author_name']);
                }
            ));
    }

    /**
     * The web page should display cached information, if available, by default.
     */
    public function test_the_web_page_should_display_cached_information_if_available_by_default()
    {
        $this->actingAs($this->user)->get('/today');

        $response = $this->actingAs($this->user)->get('/today');

        $cachedValue = Cache::get('daily_quote');

        $response->assertInertia(
            function ($page) use ($cachedValue) {
                $page->has('quote')
                    ->where('quote', function ($quote) use ($cachedValue) {
                        return $quote['quote_text'] === $cachedValue['quote']['quote_text'];
                    });
            }
        );
    }

    /**
     * If cache was used, the quote should be prefixed with an appropriate icon or “[cached]” keyword/tag.
     */
    public function test_if_cache_was_used_the_quote_should_be_prefixed_with_an_appropriate_icon_or_cached_keyword_tag()
    {
        $this->actingAs($this->user)->get('/today');

        $response = $this->actingAs($this->user)->get('/today');

        $response->assertInertia(
            fn ($page) => $page->has('quote')
                ->where('quote', function ($quote) {
                    return str_contains($quote['quote_text'], '[cached]');
                })
        );
    }

    /**
     * The same page should also show a “random inspirational image”.
     */
    public function test_the_same_page_should_also_show_a_random_inspirational_image()
    {
        $response = $this->actingAs($this->user)->get('/today');

        $response->assertInertia(
            fn ($page) => $page->has('randomInspirationalImagePath')
                ->where('randomInspirationalImagePath', function ($image) {
                    return gettype($image) === 'string';
                })
        );
    }

    /**
     * There should be a button to force a reload of the “quote of the day” with a “new” parameter (e.g., /today/new).
     */
    public function test_there_should_be_a_button_to_force_a_reload_of_the_quote_of_the_day_with_a_new_parameter()
    {
        $this->actingAs($this->user)->get('/today');

        $response = $this->actingAs($this->user)->get('/today/new');

        $response->assertInertia(
            fn ($page) => $page->has('quote')
                ->where('quote', function ($quote) {
                    return str_contains($quote['quote_text'], '[cached]') === false;
                })
        );
    }

    /**
     * There should be a button to add the “quote of the day” to the list of favorites.
     */
    public function test_there_should_be_a_button_to_add_the_quote_of_the_day_to_the_list_of_favorites()
    {
        $response = $this->actingAs($this->user)->get('/today');

        $response->assertInertia(
            fn ($page) => $page->has('isFavorite')
                ->where('isFavorite', function ($isFavorite) {
                    return $isFavorite === false;
                })
        );

        $quote = Quote::where([
            'quote_text' => $this->dailyQuoteStub['q'],
            'author_name' => $this->dailyQuoteStub['a'],
        ])->first();

        $this->user->favoriteQuotes()->attach($quote->id);

        $response = $this->actingAs($this->user)->get('/today');

        $response->assertInertia(
            fn ($page) => $page->has('isFavorite')
                ->where('isFavorite', function ($isFavorite) {
                    return $isFavorite === true;
                })
        );
    }

    /**
     * Default page when accessing “/” URI.
     */
    public function test_default_page_when_accessing_uri()
    {
        $response = $this->get('/');

        $response->assertRedirect('/today');
    }

    /**
     * The page is accessible to unauthenticated users.
     */
    public function test_the_page_is_accessible_to_unauthenticated_users()
    {
        $response = $this->get('/today');

        $response->assertOk();
    }

    /**
     * The page is accessible to authenticated/logged in users.
     */
    public function test_the_page_is_accessible_to_authenticated_logged_in_users()
    {
        $response = $this->actingAs($this->user)->get('/today');

        $response->assertOk();
    }
}
