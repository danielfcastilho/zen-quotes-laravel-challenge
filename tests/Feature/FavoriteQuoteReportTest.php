<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteQuoteReportTest extends TestCase
{
    use RefreshDatabase;

    protected $user1;
    protected $user2;
    protected $secureQuotesStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::factory()->create();

        $quote1 = Quote::factory()->create();
        $quote2 = Quote::factory()->create();
        $quote3 = Quote::factory()->create();

        $this->user1->favoriteQuotes()->attach([$quote1->id, $quote2->id, $quote3->id]);

        $this->user2 = User::factory()->create();

        $quote4 = Quote::factory()->create();

        $this->user2->favoriteQuotes()->attach([$quote1->id, $quote4->id]);
    }

    /**
     * A web page with URI of “/report-favorite-quotes” that shows a list of registered users and favorite quotes they have added to their list.
     */
    public function test_a_web_page_with_uri_of_report_favorite_quotes_that_shows_a_list_of_registered_users_and_favorite_quotes_they_have_added_to_their_list()
    {
        $response = $this->actingAs($this->user1)->get('/report-favorite-quotes');

        $user1FavoriteQuotes = $this->user1->favoriteQuotes->pluck('id')->toArray();
        $user2FavoriteQuotes = $this->user2->favoriteQuotes->pluck('id')->toArray();

        $response->assertInertia(
            fn ($page) => $page->has('users')
                ->where('users', function ($users) use ($user1FavoriteQuotes, $user2FavoriteQuotes) {
                    foreach ($users as $user) {
                        $userFavoriteQuotesIds = collect($user['favorite_quotes'])->pluck('id')->toArray();
                        if ($user['id'] === $this->user1->id) {
                            if ($userFavoriteQuotesIds !== $user1FavoriteQuotes) return false;
                        } else if ($user['id'] === $this->user2->id) {
                            if ($userFavoriteQuotesIds !== $user2FavoriteQuotes) return false;
                        } else {
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
    public function test_the_page_is_accessible_to_authenticated_users_only()
    {
        $response = $this->actingAs($this->user1)->get('/report-favorite-quotes');

        $response->assertOk();
    }

    /**
     * The page redirects to “/quotes” for unauthenticated users.
     */
    public function test_the_page_redirects_to_quotes_for_unauthenticated_users()
    {
        $response = $this->get('/report-favorite-quotes');

        $response->assertRedirect('/quotes');
    }
}
