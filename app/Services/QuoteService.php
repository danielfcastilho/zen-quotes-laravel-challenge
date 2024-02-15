<?php

namespace App\Services;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuoteService
{
    public function findOrCreate($quoteResponse, $isCached)
    {
        if (empty($quoteResponse)) return null;

        $quote = null;
        try {
            $quote = Quote::where([
                'quote_text' => $quoteResponse[0]['q'],
                'author_name' => $quoteResponse[0]['a']
            ])->first();

            if (!$quote) {
                $quote = new Quote();
                $quote->quote_text = $quoteResponse[0]['q'];
                $quote->author_name = $quoteResponse[0]['a'];
                $quote->save();
            }

            $quote->isCached = $isCached;
        } catch (\Exception $e) {
            Log::error('An error occured when trying to save quotes: ' . $e->getMessage());
        }

        return $quote;
    }

    public function findOrCreateMany($response, $isCached)
    {
        $quotes = new Collection();

        DB::beginTransaction();

        try {
            foreach ($response as $quoteResponse) {
                $quote = Quote::where([
                    'quote_text' => $quoteResponse['q'],
                    'author_name' => $quoteResponse['a']
                ])->first();

                if (!$quote) {
                    $quote = Quote::create([
                        'quote_text' => $quoteResponse['q'],
                        'author_name' => $quoteResponse['a'],
                    ]);
                } else {
                    $quote->save();
                }

                $quote->isCached = $isCached;

                $quotes->push($quote);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('An error occured when trying to save quotes: ' . $e->getMessage());
        }

        return $quotes;
    }
}
