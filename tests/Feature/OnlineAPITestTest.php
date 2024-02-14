<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnlineAPITestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A web page with URI of “/api-test” that allows us to test API endpoints.
     */
    public function test_a_web_page_with_uri_of_api_test_that_allows_us_to_test_api_endpoints()
    {
        $response = $this->get('/api-test');
        $response->assertOk();
    }

    /**
     * The page is accessible to unauthenticated users.
     */
    public function test_the_page_is_accessible_to_unauthenticated_users()
    {
        $response = $this->get('/api-test');

        $response->assertOk();
    }

    /**
     * The page is accessible to authenticated/logged in users.
     */
    public function test_the_page_is_accessible_to_authenticated_logged_in_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api-test');

        $response->assertOk();
    }
}
