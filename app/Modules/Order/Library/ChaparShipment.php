<?php

namespace App\Modules\Order\Library;

use CURLFile;

class ChaparShipment
{
    protected static string $baseUrl = 'https://api.krch.ir/v1/';
    protected static string $authToken = 'aW9zX2N1c3RvbWVyX2FwcDpUUFhAMjAxNg==';

    /**
     * ارسال درخواست به API
     */
    protected static function request(string $endpoint, array $postFields)
    {
        $curl = curl_init();

        $url = self::$baseUrl . $endpoint;

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'APP-AUTH: ' . self::$authToken
            ],

        ]);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new \Exception('Curl Error: ' . $error . ' | URL: ' . $url);
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * ارسال درخواست bulk
     */
    public static function bulk(string $jsonInput)
    {


        $postFields = [
            'input' => $jsonInput,
//            'signature' => new CURLFile(realpath('/path/to/file'))
        ];

        return self::request('bulk_import', $postFields);
    }

    /**
     * دریافت قیمت
     */
    public static function quote(array $orderData)
    {
        $inputJson = json_encode(['order' => $orderData],JSON_UNESCAPED_UNICODE);
        $postFields = [
            'input' => $inputJson
        ];

        return self::request('get_quote', $postFields);
    }
    public static function agent(){
        return self::request('fetch_agent', []);
    }
}
