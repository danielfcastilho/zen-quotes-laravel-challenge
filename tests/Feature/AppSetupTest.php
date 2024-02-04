<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AppSetupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic select to test the database connection.
     */
    public function test_database_connection_is_working()
    {
        $result = DB::select('SELECT 1 as value');

        $this->assertEquals($result[0]->value, 1);
    }

    /**
     * A basic cache test.
     */
    public function test_cache_database_connection_is_working()
    {
        $key = 'test_cache_key';
        $value = 'test_value';
        Cache::put($key, $value, 60);

        $cachedValue = Cache::get($key);

        $this->assertEquals($value, $cachedValue);

        $this->assertTrue(Cache::has($key));
    }
}
