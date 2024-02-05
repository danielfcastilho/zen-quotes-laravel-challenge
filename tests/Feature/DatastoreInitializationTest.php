<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatastoreInitializationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Datastore should be initialized with 3 users.
     */
    public function test_datastore_should_be_initialized_with_three_users()
    {
        $this->seed();

        $seededUsersCount = User::count();

        $this->assertEquals(3, $seededUsersCount, "The users table does not have the expected number of records.");
    }

    /**
     * Datastore should be initialized with a list containing 3 favorite quotes for each seeded user.
     */
    public function test_datastore_should_be_initialized_with_a_list_containing_three_favorite_quotes_for_each_seeded_user()
    {
        $this->seed();

        $seededUsers = User::all();

        foreach ($seededUsers as $seededUser) {
            $favoriteQuotesCount = $seededUser->favoriteQuotes()->count();
            $this->assertEquals(3, $favoriteQuotesCount, "The user does not have exactly 3 favorite quotes.");
        }
    }
}
