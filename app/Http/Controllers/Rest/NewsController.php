<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return News::all()->sortByDesc("published_at");
    }
}
