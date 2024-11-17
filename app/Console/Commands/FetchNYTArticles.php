<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Article;
use Carbon\Carbon;

class FetchNYTArticles extends Command
{
    protected $signature = 'fetch:nyt_articles';
    protected $description = 'Fetch articles from New York Times RSS feed and store them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Fetching articles from New York Times RSS feed using Guzzle...');

        // New York Times URL
        $rssFeedUrl = 'https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml';
        $client = new Client();

        try {
            $response = $client->get($rssFeedUrl, [
                'verify' => false  // Disable SSL certificate verification
            ]);

            $rssContent = $response->getBody()->getContents();

            $rss = simplexml_load_string($rssContent);

            if ($rss === false) {
                throw new \Exception('Failed to parse RSS feed.');
            }

            foreach ($rss->channel->item as $item) {
                $publishedAt = Carbon::parse($item->pubDate)->toDateTimeString();

                // Check if the article already exists (by URL) to avoid duplicates
                if (!Article::where('url', (string)$item->link)->exists()) {
                    Article::create([
                        'title' => (string)$item->title,
                        'description' => (string)$item->description,
                        'url' => (string)$item->link,
                        'published_at' => $publishedAt, 
                    ]);
                }
            }

            $this->info('New York Times articles fetched and stored successfully.');
        } catch (\Exception $e) {
            $this->error('Error fetching New York Times RSS feed: ' . $e->getMessage());
        }
    }
}
