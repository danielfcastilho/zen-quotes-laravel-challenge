<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The application supports authentication and authorization.
     */
    public function test_the_application_supports_authentication_and_authorization()
    {
        $user = User::factory()->create();

        /* Authentication */
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
        ]);
        $response->assertSessionHasErrors('username');

        $this->assertGuest();

        /* Authorization */
        $response = $this->get('/profile');
        $response->assertRedirect();

        /* Authentication */
        $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $this->assertAuthenticated();

        /* Authorization */
        $response = $this->get('/profile');
        $response->assertOk();
    }

    /**
     * Users can login with URI “/login” using username and password.
     */
    public function test_users_can_login_with_uri_login_using_username_and_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * The username is in the form of a proper email address containing only alphanumeric characters plus at-sign (@), and dot (.).
     */
    public function test_the_username_is_in_the_form_of_a_proper_email_address_containing_only_alphanumeric_characters_plus_at_sign_and_dot()
    {
        $invalidUsernames = [
            'testexample.com',
            'test@examplecom',
            'test@.com',
            '@example.com',
            'test@exam_ple.com',
            'test@example.c',
            'test@example..com',
            'test@.com',
            'test@exam..ple.com',
        ];

        foreach ($invalidUsernames as $invalidUsername) {
            $response = $this->post('/login', [
                'username' => $invalidUsername,
                'password' => UserFactory::defaultPassword(),
            ]);

            $response->assertSessionHasErrors('username');
        }
    }

    /**
     * Each user is assigned API login token.
     */
    public function test_each_user_is_assigned_api_login_token()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $response->assertCookie('api_token');

        $cookie = $response->getCookie('api_token');

        $this->assertNotNull($cookie);
        $this->assertNotEmpty($cookie->getValue());
    }

    /**
     * The page is accessible to unauthenticated users.
     */
    public function test_login_page_is_accessible_to_unauthenticated_users()
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    /**
     * The page is accessible to authenticated/logged in users.
     */
    public function test_login_page_is_accessible_to_authenticated_users()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $this->assertAuthenticated();

        $response = $this->get('/login');

        $response->assertOk();
    }

    /**
     * The page allows for currently authenticated users to switch to another authenticated user (with correct credentials).
     */
    public function test_page_allows_for_currently_authenticated_users_to_switch_to_another_authenticated_user()
    {
        $user1 = User::factory()->create();

        $this->post('/login', [
            'username' => $user1->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $this->assertAuthenticatedAs($user1);

        $user2 = User::factory()->create();

        $this->post('/login', [
            'username' => $user2->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $this->assertAuthenticatedAs($user2);

        $loginResponse = $this->get('/login');
        $loginResponse->assertInertia(
            fn ($page) =>
            $page->has('authenticatedUsers')
                ->where('authenticatedUsers', [$user1->username, $user2->username])
        );

        $this->post('/logout');

        $loginResponse = $this->get('/login');
        $loginResponse->assertInertia(
            fn ($page) =>
            $page->has('authenticatedUsers')
                ->where('authenticatedUsers', [$user1->username])
        );
    }

    /**
     * The login & logout process does not delete the list of favorites for a previously logged in user.
     */
    public function test_the_login_and_logout_process_does_not_delete_the_list_of_favorites_for_a_previously_logged_in_user()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $quote = Quote::for($user, 'users')->factory()->create();

        $this->post('/logout');

        $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword(),
        ]);

        $response = $this->get('/favorite-quotes');

        $response->assertViewHas('favoriteQuotes', function ($favoriteQuotes) use ($quote) {
            foreach ($favoriteQuotes as $favoriteQuote) {
                if ($favoriteQuote->id === $quote->id) {
                    return true;
                }
            }

            return false;
        });
    }
}
