<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class News extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.news';

    public function sync(Request $request)
    {
        Artisan::call('app:get-news', ['country' => $request->post('country_code')]);

        return redirect()->route('filament.pages.news');
    }
}
