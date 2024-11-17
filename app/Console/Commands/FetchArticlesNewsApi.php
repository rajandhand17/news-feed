<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Article;
use Carbon\Carbon;  
class FetchArticlesNewsApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles-news-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Fetching articles from NewsAPI...');
        
        // NewsAPI credentials
        $apiKey = '6fdfb074d97f47b19aa5f976a0a5d3de';
        $client = new Client();
        
        try {
            $response = $client->get('https://newsapi.org/v2/everything', [
                'query' => [
                    'q' => 'latest news', // You can modify the query to filter news topics
                    'apiKey' => $apiKey,
                    'language' => 'en',
                    'pageSize' => 10,
                ],
                'verify' => false, // Disable SSL verification
            ]);

            $articles = json_decode($response->getBody()->getContents())->articles;

            foreach ($articles as $article) {
                // Check if the article already exists (by URL) to avoid duplicates
                if (!Article::where('url', $article->url)->exists()) {
                    // this articles not giving back source or categories so ther is not defined them.
                    Article::create([
                        'title' => $article->title,
                        'description' => $article->description,
                        'url' => $article->url,
                        'published_at' => Carbon::parse($article->publishedAt)->toDateTimeString(),
                        
                    ]);
                }
            }

            $this->info('Articles fetched and stored successfully.');
        } catch (\Exception $e) {
            $this->error('Error fetching articles: ' . $e->getMessage());
        }
    }
}
