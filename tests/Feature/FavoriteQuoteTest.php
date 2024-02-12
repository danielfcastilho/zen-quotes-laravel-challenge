<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteQuoteTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $secureQuotesStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $quote1 = Quote::factory()->create();
        $quote2 = Quote::factory()->create();
        $quote3 = Quote::factory()->create();

        $this->user->favoriteQuotes()->attach([$quote1->id, $quote2->id, $quote3->id]);
    }

    /**
     * A web page with URI of “/favorite-quotes” that shows all quotes that have been added to the list of favorites.
     */
    public function test_a_web_page_with_uri_of_favorite_quotes_that_shows_all_quotes_that_have_been_added_to_the_list_of_favorites()
    {
        $response = $this->actingAs($this->user)->get('/favorite-quotes');

        $favoriteQuotes = $this->user->favoriteQuotes->pluck('id')->toArray();

        $response->assertInertia(
            fn ($page) => $page->has('quotes')
                ->where('quotes', function ($quotes) use ($favoriteQuotes) {
                    return $favoriteQuotes === $quotes->pluck('id')->toArray();
                })
        );
    }

    /**
     * The page is accessible to authenticated/logged in users only.
     */
    public function test_the_page_is_accessible_to_unauthenticated_users_only()
    {
        $response = $this->actingAs($this->user)->get('/favorite-quotes');

        $response->assertOk();
    }

    /**
     * The page redirects to “/quotes” for unauthenticated users.
     */
    public function test_the_page_redirects_to_quotes_for_unauthenticated_users()
    {
        $response = $this->get('/favorite-quotes');

        $response->assertRedirect('/quotes');
    }
}
