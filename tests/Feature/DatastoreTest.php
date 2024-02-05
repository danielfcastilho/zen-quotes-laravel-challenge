<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatastoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * All user information is stored in an SQLite database.
     */
    public function test_all_user_information_is_stored_in_an_sqlite_database()
    {
        $this->seed();

        $result = DB::select('SELECT COUNT(*) as users_count from users');

        $this->assertNotEmpty($result[0]->users_count);

        $connection = config('database.default');

        $driver = config("database.connections.{$connection}.driver");

        $this->assertEquals('sqlite', $driver, "The application is not using an SQLite database.");
    }

    /**
     * All quote information is stored in an SQLite database.
     */
    public function test_all_quote_information_is_stored_in_an_sqlite_database()
    {
        $this->seed();

        $result = DB::select('SELECT COUNT(*) as quotes_count from quotes');

        $this->assertNotEmpty($result[0]->quotes_count);

        $connection = config('database.default');

        $driver = config("database.connections.{$connection}.driver");

        $this->assertEquals('sqlite', $driver, "The application is not using an SQLite database.");
    }
}
