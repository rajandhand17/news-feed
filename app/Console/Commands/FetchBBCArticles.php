<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Article;
use Carbon\Carbon;

class FetchBBCArticles extends Command
{
    protected $signature = 'fetch:bbc_articles';
    protected $description = 'Fetch articles from BBC News and store them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Fetching articles from BBC News RSS feed using Guzzle...');

        // BBC News URL
        $rssFeedUrl = 'http://feeds.bbci.co.uk/news/rss.xml';
        $client = new Client();

        try {
            // send the GET request to fetch the RSS feed
            $response = $client->get($rssFeedUrl, [
                'verify' => false  // disable SSL certificate verification
            ]);

            // get the body of the response (RSS XML)
            $rssContent = $response->getBody()->getContents();

            //    Parse the RSS feed content using simplexml
            $rss = simplexml_load_string($rssContent);

            if ($rss === false) {
                throw new \Exception('Failed to parse RSS feed.');
            }

            // loop through each item in the RSS feed
            foreach ($rss->channel->item as $item) {
                // convert the published date to MySQL-compatible format
                $publishedAt = Carbon::parse($item->pubDate)->toDateTimeString();

                // check if the article already exists (by URL) to avoid duplicates
                if (!Article::where('url', (string)$item->link)->exists()) {
                    Article::create([
                        'title' => (string)$item->title,
                        'description' => (string)$item->description,
                        'url' => (string)$item->link,
                        'published_at' => $publishedAt,  
                    ]);
                }
            }

            $this->info('BBC News articles fetched and stored successfully.');
        } catch (\Exception $e) {
            $this->error('Error fetching BBC News RSS feed: ' . $e->getMessage());
        }
    }
}
