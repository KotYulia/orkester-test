<?php

namespace App\Console\Commands;

use App\Models\News;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-news {country}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $country = $this->argument('country');
        $news_url = env('NEWSAPI_URL') . 'apiKey=' . env('NEWSAPI_KEY') . '&country=' . $country;
        $client = new Client(['http_errors' => false]);

        try {
            $response = $client->request('GET', $news_url, [
                'headers' => [
                    'Accept' => 'application/json',
                    "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8;",
                ],
            ]);

            $result = json_decode($response->getBody()->getContents());

            if ($result->totalResults > 0) {
                foreach ($result->articles as $article) {
                    if (!News::where('url', '=', $article->url)->exists()) {
                        $published_at = date('Y-m-d H:i:s', strtotime($article->publishedAt));
                        $new_article = new News();

                        $new_article->source_id = $article->source->id;
                        $new_article->source_name = $article->source->name;
                        $new_article->author = $article->author;
                        $new_article->title = $article->title;
                        $new_article->description = $article->description;
                        $new_article->url = $article->url;
                        $new_article->urlToImage = $article->urlToImage;
                        $new_article->content = $article->content;
                        $new_article->published_at = $published_at;

                        $new_article->save();
                    }
                }
            }
        } catch (Guzzle\Http\Exception\BadResponseException $e) {
            echo $e->getMessage();
        }
    }
}
