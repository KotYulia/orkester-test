<?php

namespace App\Filament\Pages;

use App\Http\Requests\GetNewsRequest;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class NewsApiSync extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.news-api-sync';

    public function sync(GetNewsRequest $request)
    {
        $request->validated($request->all());

        Artisan::call('app:get-news', ['country' => $request->post('country_code')]);

        return redirect()->back();
    }
}
