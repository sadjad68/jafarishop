<?php

function api_asset($url)
{
    $base_url = config('cms-assistant.asset-url');
    return $base_url . '/' . $url;
}
