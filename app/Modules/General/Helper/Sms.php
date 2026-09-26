<?php

namespace App\Modules\General\Helper;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Setting\Entities\Setting;

class Sms
{
    private $token;
    private $baseUrl = "https://api.kavenegar.com/v1";
    private $sender;

    public function __construct()
    {
        try {
            $kave = Setting::where('key', 'kavenegar_key')->whereNotNull('value')->first();
            $sender = Setting::where('key', 'kavenegar_sender')->whereNotNull('value')->first();
            $this->token = $kave ? $kave['value'] : null;
            $this->sender = $sender ? $sender['value'] : null;
        } catch (\Throwable $e) {
            Log::error('Failed to load Kavenegar settings: ' . $e->getMessage());
            $this->token = null;
            $this->sender = null;
        }
    }

    public function execute(string $url)
    {
        if (!isset($this->token)) {
            return Redirect::back()
                ->with('error', 'لطفا با پشتیبانی تماس بگیرید ');
        }

        return $this->executeWithRetry($url);
    }

    protected function executeWithRetry(string $url, int $maxAttempts = 3)
    {
        $lastError = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $response = $this->performRequest($url, $lastError);

            if ($response !== false) {
                return $response;
            }

            if ($attempt < $maxAttempts) {
                usleep(500000 * $attempt);
            }
        }

        if ($lastError) {
            Log::error("Kavenegar request failed after {$maxAttempts} attempts: {$lastError} | URL: {$url}");
        }

        return false;
    }

    protected function performRequest(string $url, ?string &$lastError = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, $this->buildCurlOptions("$this->baseUrl/$this->token/$url"));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $lastError = curl_error($curl);
            Log::warning("Kavenegar cURL attempt failed ({$lastError}) for {$url}");
        }

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($response === false) {
            return false;
        }

        if ($http_status != 200) {
            Log::error("HTTP status {$http_status} received from {$url}");
            return false;
        }

        return $response;
    }

    protected function buildCurlOptions(string $fullUrl, ?array $postData = null): array
    {
        $options = [
            CURLOPT_URL => $fullUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'TemplateCMS/1.0',
        ];

        if (defined('CURL_SSLVERSION_TLSv1_2')) {
            $options[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;
        }

        if ($postData !== null) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($postData);
        } else {
            $options[CURLOPT_CUSTOMREQUEST] = 'GET';
        }

        return $options;
    }

    public function sendLookup(string $template, array $tokens, string|array $mobile, string $type = "sms")
    {
        $mobiles = $this->extractMobiles($mobile);
        if (empty($mobiles)) {
            return false;
        }

        if (env("APP_ENV") == "local") {
            $mobiles = [env("LOCAL_MOBILE")];
        }

        if (app()->environment('local') && filter_var(env('SMS_MOCK_LOCAL', false), FILTER_VALIDATE_BOOLEAN)) {
            $token = $tokens['token'] ?? 'N/A';
            foreach ($mobiles as $receptor) {
                Log::info("[LOCAL SMS MOCK] OTP for {$receptor}: {$token}");
            }
            return json_encode(['return' => ['status' => 200]]);
        }

        $tokens_query = http_build_query($tokens);
        $lastResponse = false;

        foreach ($mobiles as $receptor) {
            $url = "verify/lookup.json?receptor=$receptor&type=$type&template=$template&$tokens_query";
            try {
                $lastResponse = $this->execute($url);
            } catch (\Exception $e) {
                Log::info($e);
                $lastResponse = false;
            }

            if ($lastResponse === false && app()->environment('local')) {
                $token = $tokens['token'] ?? 'N/A';
                Log::info("[LOCAL SMS FALLBACK] Kavenegar unreachable. OTP for {$receptor}: {$token}");
            }
        }

        return $lastResponse;
    }

    /**
     * استخراج یک یا چند شماره موبایل از رشته ورودی (تک شماره یا چند شماره با جداکننده ~~##)
     */
    protected function extractMobiles(string $mobile): array
    {
        $parts = preg_split('/~~##/', $mobile);
        $mobiles = [];

        foreach ($parts as $part) {
            $normalized = $this->normalizeMobile($part);
            if ($normalized !== null && $normalized !== '') {
                $mobiles[] = $normalized;
            }
        }

        return array_values(array_unique($mobiles));
    }

    public function sendSms(string $text, string $mobile)
    {
        $testNumber = env('SMS_TEST_NUMBER', '09367300130');
        $message = urlencode($text);
        if (env("APP_ENV") == "local") {
            $receptor = $testNumber;
        } else {
            $receptor = $mobile;
        }
        $url = "sms/send.json?receptor=$receptor&message=$message&sender={$this->sender}";
        try {
            $res = $this->execute($url);
            if ($res === false) {
                return ["success" => false, "message" => "Connection Error"];
            }
            $res_json = json_decode($res);
            if (isset($res_json->return) && $res_json->return->status == 200) {
                return ["success" => true];
            } else {
                $error_message = isset($res_json->return->message) ? $res_json->return->message : 'Unknown error';
                return ["success" => false, "message" => $error_message];
            }
        } catch (\Exception $e) {
            Log::error("Exception during sending SMS: " . $e->getMessage());
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    protected function normalizeMobile($mobile): array|string|null
    {
        $mobile = trim($mobile);
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        if (str_starts_with($mobile, '9') && strlen($mobile) === 10) {
            $mobile = '0' . $mobile;
        }
        return $mobile;
    }

    /**
     * ارسال گروهی پیامک با متد sendarray کاوه نگار
     * محدودیت: حداکثر 200 پیام در هر درخواست
     */
    public function sendSmsArray(array $messages)
    {
        if (empty($messages)) return ["success" => false, "message" => "Empty data"];

        $receptors = [];
        $senders = [];
        $texts = [];

        $isLocal = env("APP_ENV") == "local";

        $testNumber = env('SMS_TEST_NUMBER', '09367300130');
        foreach ($messages as $row) {
            $receptors[] = $isLocal ? $testNumber : $row['receptor'];
            $texts[] = $row['message'];
            $senders[] = $this->sender;
        }

        $postData = [
            "receptor" => json_encode($receptors),
            "sender" => json_encode($senders),
            "message" => json_encode($texts),
        ];

        $res = $this->executePost("sms/sendarray.json", $postData);
        if (!$res) return ["success" => false, "message" => "Connection Error"];

        $json = json_decode($res, true);

        if (isset($json['return']) && $json['return']['status'] == 200) {
            return ["success" => true, "entries" => $json['entries'] ?? []];
        }

        return [
            "success" => false,
            "message" => $json['return']['message'] ?? "API error",
            "status_code" => $json['return']['status'] ?? null
        ];
    }

    public function executePost(string $url, $postData = null)
    {
        if (!$this->token) {
            Log::error("Sms Helper: Token is missing.");
            return false;
        }

        return $this->executePostWithRetry($url, $postData);
    }

    protected function executePostWithRetry(string $url, ?array $postData = null, int $maxAttempts = 3)
    {
        $lastError = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $curl = curl_init();
            curl_setopt_array($curl, $this->buildCurlOptions("$this->baseUrl/$this->token/$url", $postData));
            $response = curl_exec($curl);
            $error = curl_error($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($error) {
                $lastError = $error;
                Log::warning("Kavenegar POST attempt failed ({$error}) | URL: {$url}");
            } elseif ($response !== false && $http_status === 200) {
                return $response;
            } elseif ($response !== false) {
                Log::error("HTTP status {$http_status} received from {$url}");
            }

            if ($attempt < $maxAttempts) {
                usleep(500000 * $attempt);
            }
        }

        if ($lastError) {
            Log::error("Kavenegar POST failed after {$maxAttempts} attempts: {$lastError} | URL: {$url}");
        }

        return false;
    }

    /**
     * برای تست با ارسال کننده کاستوم
     */
    public function sendSmsArrayTest(array $messages, ?string $overrideSender = null): array
    {
        if (empty($messages)) return ["success" => false, "message" => "Empty data"];

        $receptors = [];
        $senders = [];
        $texts = [];

        foreach ($messages as $row) {
            $receptors[] = $row['receptor'];
            $texts[] = $row['message'];
            $senders[] = $overrideSender ?: $this->sender;
        }

        $postData = [
            "receptor" => json_encode($receptors),
            "sender" => json_encode($senders),
            "message" => json_encode($texts),
        ];

        $res = $this->executePost("sms/sendarray.json", $postData);
        if (!$res) return ["success" => false, "message" => "Connection Error"];

        $json = json_decode($res, true);

        if (isset($json['return']) && $json['return']['status'] == 200) {
            return ["success" => true];
        }

        return [
            "success" => false,
            "message" => $json['return']['message'] ?? "API error",
            "status_code" => $json['return']['status'] ?? null
        ];
    }
}
