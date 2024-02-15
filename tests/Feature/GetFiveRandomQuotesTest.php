<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetFiveRandomQuotesTest extends TestCase
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
     * Create a console/shell command “php artisan Get-FiveRandomQuotes” for Feature: Five Random Quotes.
     */
    public function test_create_a_console_command_php_artisan_getfiverandomquotes_for_feature_five_random_quotes()
    {
        $this->artisan('Get-FiveRandomQuotes')
            ->assertExitCode(0);
    }

    /**
     * The console/shell command should return cached information, if available, by default.
     */
    public function test_the_console_command_should_return_cached_information_if_available_by_default()
    {
        $this->artisan('Get-FiveRandomQuotes');

        $cachedQuotes = Cache::get('random_quotes');

        $testedCommand = $this->artisan('Get-FiveRandomQuotes');
        foreach ($cachedQuotes as $quote) {
            $testedCommand->expectsOutputToContain("{$quote['q']} - {$quote['a']}");
        }
        $testedCommand->assertExitCode(0);
    }

    /**
     * If cache was used, each of the quotes should be prefixed with “[cached] ” keyword/tag.
     */
    public function test_if_cache_was_used_each_of_the_quotes_should_be_prefixed_with_cached_keyword_tag()
    {
        $this->artisan('Get-FiveRandomQuotes')
            ->doesntExpectOutputToContain('[cached]')
            ->assertExitCode(0);

        $this->artisan('Get-FiveRandomQuotes')
            ->expectsOutputToContain('[cached]')
            ->assertExitCode(0);
    }

    /**
     * The console/shell command shares cache with the Feature: Five Random Quotes
     */
    public function test_the_console_command_shares_cache_with_the_feature_five_random_quotes()
    {
        $this->get('/quotes');

        $cachedQuotes = Cache::get('random_quotes');

        $testedCommand = $this->artisan('Get-FiveRandomQuotes');
        foreach ($cachedQuotes as $quote) {
            $testedCommand->expectsOutputToContain("{$quote['q']} - {$quote['a']}");
        }
        $testedCommand->assertExitCode(0);
    }

    /**
     * The console/shell command can be forced to fetch and return fresh set of results (and update the cache) with a “new” parameter (e.g., “php artisan Get-FiveRandomQuotes --new”).
     */
    public function test_the_console_command_can_be_forced_to_fetch_and_return_fresh_set_of_results_with_a_new_parameter()
    {
        $this->artisan('Get-FiveRandomQuotes')
            ->doesntExpectOutputToContain('[cached]')
            ->assertExitCode(0);

        $this->artisan('Get-FiveRandomQuotes --new')
            ->doesntExpectOutputToContain('[cached]')
            ->assertExitCode(0);
    }

    /**
     * The console/shell command is accessible to unauthenticated users.
     */
    public function test_the_console_command_is_accessible_to_unauthenticated_users()
    {
        $this->artisan('Get-FiveRandomQuotes')
            ->assertSuccessful()
            ->assertExitCode(0);
    }

    /**
     * The console/shell command is accessible to authenticated/logged in users.
     */
    public function test_the_console_command_is_accessible_to_authenticated_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->artisan('Get-FiveRandomQuotes')
            ->assertSuccessful()
            ->assertExitCode(0);
    }
}
