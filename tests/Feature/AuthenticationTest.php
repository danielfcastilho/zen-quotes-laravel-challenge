<?php

namespace Tests\Feature;

use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\AuthenticationHelper;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use AuthenticationHelper, RefreshDatabase;

    /**
     * The application supports authentication and authorization.
     */
    public function test_the_application_supports_authentication_and_authorization()
    {
        /* Authentication */
        $response = $this->post('/login', [
            'username' => 'wrong-username',
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized();
        $this->assertGuest();

        /* Authorization */
        $response = $this->get('/report-favorite-quotes');
        $response->assertNotFound();

        /* Authentication */
        $this->registerAndLoginUser();

        $this->assertAuthenticated();

        /* Authorization */
        $response = $this->get('/report-favorite-quotes');

        $response->assertOk();
    }

    /**
     * Users can login with URI “/login” using username and password.
     */
    public function test_users_can_login_with_uri_login_using_username_and_password()
    {
        $token = $this->registerAndLoginUser();

        $this->assertNotNull($token);
    }

    /**
     * The username is in the form of a proper email address containing only alphanumeric characters plus at-sign (@), and dot (.).
     */
    public function test_the_username_is_in_the_form_of_a_proper_email_address_containing_only_alphanumeric_characters_plus_at_sign_and_dot()
    {
        $invalidEmails = [
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

        foreach ($invalidEmails as $email) {
            $response = $this->post('/login', [
                'username' => $email,
                'password' => 'password',
            ]);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Each user is assigned API login token.
     */
    public function test_each_user_is_assigned_api_login_token()
    {
        $token = $this->registerAndLoginUser();

        $this->assertNotNull($token);
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
        $this->registerAndLoginUser();

        $response = $this->get('/login');

        $response->assertOk();
    }

    /**
     * The page allows for currently authenticated users to switch to another authenticated user (with correct credentials).
     */
    public function test_page_allows_for_currently_authenticated_users_to_switch_to_another_authenticated_user()
    {
        $user1 = [
            'email' => 'user1@example.com',
            'password' => 'password'
        ];

        $this->registerUser($user1);
        $this->loginUser($user1['email'], $user1['password']);

        $user2 = [
            'email' => 'user2@example.com',
            'password' => 'password'
        ];

        $this->registerUser($user2);
        $this->loginUser($user2['email'], $user2['password']);

        $loginResponse = $this->get('/login');
        $loginResponse->assertViewHas('authenticatedUsers', function ($authenticatedUsers) {
            return count($authenticatedUsers) == 2;
        });
    }

    /**
     * The login & logout process does not delete the list of favorites for a previously logged in user.
     */
    public function test_the_login_and_logout_process_does_not_delete_the_list_of_favorites_for_a_previously_logged_in_user()
    {
        $quote = Quote::factory()->create();

        $user = [
            'email' => 'user1@example.com',
            'password' => 'password'
        ];

        $this->registerUser($user);

        $this->loginUser($user['email'], $user['password']);

        $this->post('/favorite-quotes', ['id' => $quote->id]);

        $this->post('/logout');

        $this->loginUser($user['email'], $user['password']);

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
