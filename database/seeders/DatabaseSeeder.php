<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create();

            $quoteIds = [];
            for ($j = 0; $j < 3; $j++) {
                $quote = Quote::factory()->create();
                $quoteIds[] = $quote->id;
            }

            $user->favoriteQuotes()->attach($quoteIds);
        }
    }
}
