<?php

namespace App\Console\Commands;

use App\Facades\NewsApi;
use App\Models\News;
use Exception;
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
        try {
            $country = $this->argument('country');
            NewsApi::getNews($country);

            $this->line('Successfully added news to the database.');
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 2;
        }
    }
}
