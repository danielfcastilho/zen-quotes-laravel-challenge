<?php

namespace App\Console\Commands;

use App\Models\Quote;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GetFiveRandomQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Get-FiveRandomQuotes
                            {--new : force the command to fetch and return a fresh set of results}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Console/Shell Command for Five Random Quotes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $new = (bool)$this->option('new');

        $apiUrl = "https://zenquotes.io/api/";
        $cacheKey = 'random_quotes';

        $cachedData = Cache::get($cacheKey);

        if ($cachedData && !$new) {
            foreach ($cachedData['quotes'] as $quote) {
                $this->line("{$quote['quote_text']} - {$quote['author_name']}");
            }
            return 0;
        }

        $response = Http::get($apiUrl . 'quotes');

        $quotesResponse = $response->json();

        $quotes = new Collection();
        foreach ($quotesResponse as $key => $quoteResponse) {
            if ($key === 5) {
                break;
            }
            $quote = Quote::where([
                'quote_text' => $quoteResponse['q'],
                'author_name' => $quoteResponse['a']
            ])->first();

            if (!$quote) {
                $quote = new Quote();
                $quote->quote_text = $quoteResponse['q'];
                $quote->author_name = $quoteResponse['a'];
                $quote->save();
            }

            $this->line("{$quote->quote_text} - {$quote->author_name}");

            $quotes->push($quote);
        }

        Cache::put($cacheKey, [
            'quotes' => $quotes->map(function ($quote) {
                $quote->quote_text = '[cached]' . $quote->quote_text;
                return $quote;
            })->toArray(),
        ], 30);
    }
}
