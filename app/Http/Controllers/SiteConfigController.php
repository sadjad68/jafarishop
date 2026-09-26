<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SiteConfigController
{
    public function setConfigSitesData()
    {
        $response = Http::withoutVerifying()->get(env('CONFIG_SITE_URL'));
        $data = $response->json();

        $jsonData = json_encode($data);
        Storage::disk('local')->put('private/config-sites-data.json', $jsonData);
        return response()->json(['message' => 'Data fetched and stored successfully']);
    }
}
