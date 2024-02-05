<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CacheTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Cache data should be stored in an SQLite database.
     */
    public function test_cache_data_should_be_stored_in_an_sqlite_database()
    {
        $key = 'test_cache_key';
        $value = 'test_value';
        Cache::put($key, $value, 60);

        $cachedValue = Cache::get($key);

        $this->assertEquals($value, $cachedValue);

        $this->assertTrue(Cache::has($key));

        $cacheDriver = config('cache.default');
        $this->assertEquals('database', $cacheDriver, "Cache driver is not set to 'database'.");

        $cacheRecord = DB::table('cache')->where('key', 'like', '%' . $key . '%')->first();
        $this->assertNotNull($cacheRecord, "Cache record for key {$key} not found in the database.");
    }
}
