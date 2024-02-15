<?php

namespace App\Services;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Collection;

class QuoteService
{
    public function findOrCreate($response, $isCached)
    {
        if (!$quote = Quote::where([
            'quote_text' => $response[0]['q'],
            'author_name' => $response[0]['a']
        ])->first()) {
            $quote = new Quote();
            $quote->quote_text = $response[0]['q'];
            $quote->author_name = $response[0]['a'];
            $quote->save();
        }

        $quote->isCached = $isCached;

        return $quote;
    }

    public function findOrCreateMany($response, $isCached)
    {
        $quotes = new Collection();
        foreach ($response as $quoteResponse) {
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

            $quote->isCached = $isCached;

            $quotes->push($quote);
        }
        return $quotes;
    }
}
