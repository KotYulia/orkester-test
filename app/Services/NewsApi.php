<?php

namespace App\Services;

use App\Models\News;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Validation\ValidationException;

class NewsApi implements \App\Contracts\NewsApi
{
    private string $api_key;

    private string $api_url;

    public function __construct()
    {
        $this->api_key = config('external-apis.news_api.key');
        $this->api_url = config('external-apis.news_api.url');
    }

    /**
     * @param $country
     * @return string|bool
     * @throws GuzzleException
     */
    public function getNews($country) : string|bool
    {
        $news_url = $this->api_url . 'apiKey=' . $this->api_key . '&country=' . $country;
        $client = new Client();

        try {
            $response = $client->request('GET', $news_url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8;',
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
                return true;
            } elseif ($result->totalResults === 0) {
                throw ValidationException::withMessages(['Country code is incorrect']);
            } else {
                throw ValidationException::withMessages(['Your API key is invalid or incorrect.']);
            }
        } catch (TransferException $e) {
            return $e->getMessage();
        }
    }
}
