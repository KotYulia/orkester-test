<?php
return [
    'news_api' => [
        'key' => env('NEWSAPI_KEY'),
        'url' => env('NEWSAPI_URL', 'https://newsapi.org/v2/top-headlines?'),
    ],
];
