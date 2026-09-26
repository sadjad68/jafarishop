<?php

namespace App\Modules\Product\Library;

class TgjuSilver
{
    protected $url;
    protected $weight;

    public function __construct($weight)
    {
        $this->weight = $weight;
        $this->url = 'https://call2.tgju.org/ajax.json?rev=Nl7qGkX7LucSa90UsS0y6MSjHzbLrQ5T3ksz4GJqb1TLhakM32gkYK4rNHyW';

    }

    public function fetchData()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new \Exception("CURL Error: $error");
        }

        curl_close($curl);
        $current_sliver_price = json_decode($response, true)['current']['silver_999']['p'];
        $current_price = (str_replace(',','',$current_sliver_price) / 100) * (92.5 * 2);

        return round((intval($current_price) * intval($this->weight)) / 10);

    }
}
