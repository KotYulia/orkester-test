<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;

class NewsController extends Controller
{
    public function index(): NewsResource
    {
        return new NewsResource(News::all()->sortByDesc("published_at"));
    }
}
