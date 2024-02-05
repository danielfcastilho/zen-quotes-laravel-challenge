<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class QuoteOfTheDayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A web page with URI of “/today” that shows “quote of the day”.
     */
    public function test_a_web_page_uri_of_today_that_shows_quote_of_the_day()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/today');

        $response->assertInertia(function ($page) {
            $page->has('quoteOfTheDay')
                ->where('quoteOfTheDay', function ($quote) {
                    $this->assertNotEmpty($quote['quoteOfTheDay']);
                });
        });
    }

    /**
     * The web page should display cached information, if available, by default.
     */
    public function test_the_page_should_display_cached_information_if_available_by_default()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/today');

        $response = $this->actingAs($user)->get('/today');

        $cachedValue = Cache::get('quoteOfTheDay');

        $response->assertInertia(
            function ($page) use ($cachedValue) {
                $page->has('quoteOfTheDay')
                    ->where('quoteOfTheDay', function ($quote) use ($cachedValue) {
                        $this->assertEquals($quote['quote_text'], $cachedValue, "The cached quote does not match the displayed quote.");
                    });
            }
        );
    }

    /**
     * If cache was used, the quote should be prefixed with an appropriate icon or “[cached]” keyword/tag.
     */
    public function test_if_cache_was_used_the_quote_should_be_prefixed_with_an_appropriate_icon_or_cached_keyword_tag()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/today');

        $response = $this->actingAs($user)->get('/today');

        $response->assertInertia(
            fn ($page) => $page->has('quoteOfTheDay')
                ->where('quoteOfTheDay', function ($quote) {
                    return str_contains($quote['quote_text'], '[cached]');
                })
        );
    }

    /**
     * The same page should also show a “random inspirational image”.
     */
    public function test_the_same_page_should_also_show_a_random_inspirational_image()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/today');

        $response->assertInertia(
            fn ($page) => $page->has('randomInspirationalImage')
                ->where('randomInspirationalImage', function ($image) {
                    return gettype($image) === 'string';
                })
        );
    }

    /**
     * There should be a button to force a reload of the “quote of the day” with a “new” parameter (e.g., /today/new).
     */
    public function test_there_should_be_a_button_to_force_a_reload_of_the_quote_of_the_day_with_a_new_parameter()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/today');

        $response->assertSee('/today/new');
    }

    /**
     * There should be a button to add the “quote of the day” to the list of favorites.
     */
    public function test_there_should_be_a_button_to_add_the_quote_of_the_day_to_the_list_of_favorites()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/today');

        $response->assertSee('Add to Favorites');
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
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/today');

        $response->assertOk();
    }
}
