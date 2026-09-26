<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SetConfigSites extends Command
{
    protected $signature = 'config:sites';
    protected $description = 'Set config sites via hamgaman API';

    public function handle()
    {
        $this->info("Recieving data from hamgaman API");

        $response = Http::withoutVerifying()->get(env('CONFIG_SITE_URL'));
        $data = $response->json();

        $jsonData = json_encode($data);
        Storage::disk('local')->put('private/config-sites-data.json', $jsonData);

        $this->info("Sites config put in storage successfully");
    }
}