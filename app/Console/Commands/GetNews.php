<?php

namespace App\Console\Commands;

use App\Facades\NewsApi;
use App\Models\News;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
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
        NewsApi::getNews($country);

        $this->line('Successfully added news to the database.');
    }
}
