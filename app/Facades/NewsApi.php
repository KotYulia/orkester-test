<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


class NewsApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'news_api';
    }
}
