<?php

namespace App\Console\Commands;

use App\Enums\StrategyType;
use App\Http\Resources\QuoteResource;
use App\Services\Api\ApiService;
use App\Services\QuoteService;
use Illuminate\Console\Command;

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

    public function __construct(protected ApiService $apiService, protected QuoteService $quoteService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $new = (bool)$this->option('new');

        $randomQuotesResponse = $this->apiService->fetchData(StrategyType::RandomQuotes, $new);

        $randomQuotes = $this->quoteService->findOrCreateMany($randomQuotesResponse->data, $randomQuotesResponse->fromCache);

        $randomQuotesCollection = QuoteResource::collection($randomQuotes)->resolve();

        foreach ($randomQuotesCollection as $quote) {
            $this->line("{$quote['quote_text']} - {$quote['author_name']}");
        }
    }
}
