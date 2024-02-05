<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebRegistrationForUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The application supports user online registration at URI “/register”.
     */
    public function test_the_application_supports_user_online_registration_at_uri_register()
    {
        $this->post('/register', [
            'username' => 'test@example.com',
            'password' => UserFactory::defaultPassword(),
            'password_confirmation' => UserFactory::defaultPassword(),
        ]);

        $this->assertDatabaseHas('users', ['username' => 'test@example.com']);
    }

    /**
     * The username is in the form of a proper email address containing only alphanumeric characters plus at-sign (@), and dot (.).
     */
    public function test_the_username_is_in_the_form_of_a_proper_email_address_containing_only_alphanumeric_characters_plus_at_sign_and_dot()
    {
        $invalidUsernames = [
            'testexample.com',
            'test@.com',
            '@example.com',
            'test@exam_ple.com',
            'test@example..com',
            'test@.com',
            'test@exam..ple.com',
        ];

        foreach ($invalidUsernames as $invalidUsername) {
            $response = $this->post('/register', [
                'username' => $invalidUsername,
                'password' => UserFactory::defaultPassword(),
                'password_confirmation' => UserFactory::defaultPassword(),
            ]);

            $response->assertSessionHasErrors('username');
        }
    }

    /**
     * Passwords must not be stored in clear text format.
     */
    public function test_passwords_must_not_be_stored_in_clear_text_format()
    {
        $this->post('/register', [
            'username' => 'test@example.com',
            'password' => UserFactory::defaultPassword(),
            'password_confirmation' => UserFactory::defaultPassword(),
        ]);

        $user = User::where('username', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNotEquals(UserFactory::defaultPassword(), $user->password);
        $this->assertTrue(Hash::check(UserFactory::defaultPassword(), $user->password));
    }

    /**
     * The page is accessible to unauthenticated users only.
     */
    public function test_the_page_is_accessible_to_unauthenticated_users_only()
    {
        $unauthenticatedResponse = $this->get('/register');

        $unauthenticatedResponse->assertOk();

        $user = User::factory()->create();

        $authenticatedResponse = $this->post('/login', [
            'username' => $user->username,
            'password' => UserFactory::defaultPassword()
        ]);
        $authenticatedResponse->assertRedirect('/today');
    }
}
